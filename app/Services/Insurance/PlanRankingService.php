<?php

namespace App\Services\Insurance;

use App\Models\InsurancePlan;
use App\Models\Pet;
use App\Support\Insurance\Data\RankablePetProfileData;
use App\Support\Insurance\Data\RankablePlanSnapshotData;
use App\Support\Pets\PetBreedMatcher;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PlanRankingService
{
    public const DEFAULT_ALGORITHM_VERSION = 'v1';

    public function __construct(
        private readonly PetProfileNormalizer $petProfileNormalizer,
        private readonly EligibilityFilter $eligibilityFilter,
    ) {
    }

    public function rankForPet(Pet $pet, Collection $plans, bool $includeBreakdown = true): array
    {
        $petProfile = $this->petProfileNormalizer->normalize($pet);
        $evaluatedPlanCount = $plans->count();

        $eligiblePlans = $plans
            ->map(fn (InsurancePlan $plan): array => $this->scorePlan($plan, $petProfile))
            ->filter(fn (array $plan): bool => $plan['eligibility']['eligible'])
            ->sort(function (array $left, array $right): int {
                return $right['final_score'] <=> $left['final_score']
                    ?: $right['exposure_priority'] <=> $left['exposure_priority']
                    ?: strcmp($right['updated_at'], $left['updated_at'])
                    ?: $left['plan_id'] <=> $right['plan_id'];
            })
            ->values();

        $rankedPlans = $eligiblePlans->map(function (array $plan, int $index) use ($includeBreakdown): array {
            $payload = [
                'plan_id' => $plan['plan_id'],
                'final_score' => $plan['final_score'],
                'coverage_fit' => $plan['coverage_fit'],
                'claimability' => $plan['claimability'],
                'sponsor_boost' => $plan['sponsor_boost'],
                'ranking_position' => $index + 1,
                'eligibility' => $plan['eligibility'],
                'algorithm_version' => $plan['algorithm_version'],
            ];

            if ($includeBreakdown) {
                $payload['breakdown'] = $plan['breakdown'];
            }

            return $payload;
        })->all();

        return [
            'plans' => $rankedPlans,
            'evaluated_plan_count' => $evaluatedPlanCount,
            'algorithm_version' => collect($rankedPlans)->pluck('algorithm_version')->filter()->first()
                ?? $plans->pluck('algorithm_version')->filter()->first()
                ?? self::DEFAULT_ALGORITHM_VERSION,
        ];
    }

    public function evaluatePlanForPet(Pet $pet, InsurancePlan $plan, bool $includeBreakdown = true): array
    {
        $payload = $this->scorePlan($plan, $this->petProfileNormalizer->normalize($pet));

        return [
            'plan_id' => $payload['plan_id'],
            'final_score' => $payload['final_score'],
            'coverage_fit' => $payload['coverage_fit'],
            'claimability' => $payload['claimability'],
            'sponsor_boost' => $payload['sponsor_boost'],
            'eligibility' => $payload['eligibility'],
            'algorithm_version' => $payload['algorithm_version'],
            'breakdown' => $includeBreakdown ? $payload['breakdown'] : null,
        ];
    }

    private function scorePlan(InsurancePlan $plan, RankablePetProfileData $petProfile): array
    {
        $snapshot = RankablePlanSnapshotData::fromInsurancePlan($plan);
        $risk = $this->calculateRisk($petProfile->toArray());
        $eligibility = $this->eligibilityFilter->evaluate($plan, $petProfile);
        $coverageFit = $eligibility['eligible']
            ? $this->coverageFit($snapshot, $plan, $petProfile)
            : 0.0;
        $claimability = $eligibility['eligible']
            ? $this->claimability($snapshot)
            : 0.0;
        $weightMultiplier = $this->weightMultiplier($snapshot);
        $sponsorMultiplier = $this->sponsorMultiplier($snapshot);
        $sponsorBoostPoints = (int) round(max(($sponsorMultiplier - 1.0) * 100, 0));

        $finalScore = $eligibility['eligible']
            ? (int) min(100, round(($risk['risk_score'] * $weightMultiplier) * $coverageFit * $claimability * $sponsorMultiplier))
            : 0;

        return [
            'plan_id' => $plan->id,
            'final_score' => $finalScore,
            'coverage_fit' => round($coverageFit, 2),
            'claimability' => round($claimability, 2),
            'sponsor_boost' => $sponsorBoostPoints,
            'exposure_priority' => (int) ($snapshot->rankingStrategySnapshot['exposure_priority'] ?? 0),
            'breakdown' => $this->breakdown(
                $finalScore,
                $risk['risk_score'],
                $weightMultiplier,
                $coverageFit,
                $claimability,
                $sponsorMultiplier,
            ),
            'eligibility' => $eligibility,
            'updated_at' => $plan->source_updated_at?->toISOString() ?? $plan->updated_at?->toISOString() ?? now()->toISOString(),
            'algorithm_version' => $plan->algorithm_version ?? self::DEFAULT_ALGORITHM_VERSION,
        ];
    }

    private function calculateRisk(array $petProfile): array
    {
        $ageYears = $this->ageInYears($petProfile['birth_date'] ?? null);
        $ageFactor = $ageYears === null
            ? 0.3
            : min(1, max(0.05, $ageYears / 15));

        $medicalHistoryCount = count($petProfile['medical_history'] ?? []);
        $chronicConditionCount = count($petProfile['chronic_conditions'] ?? []);
        $healthFactor = min(1, ($medicalHistoryCount * 0.15) + ($chronicConditionCount * 0.25) + 0.1);

        $riskScore = (int) round((($ageFactor * 0.4) + ($healthFactor * 0.6)) * 100);

        return [
            'risk_score' => $riskScore,
            'risk_vector' => [
                'age' => round($ageFactor, 2),
                'health' => round($healthFactor, 2),
            ],
            'risk_level' => match (true) {
                $riskScore >= 75 => 'high',
                $riskScore >= 40 => 'medium',
                default => 'low',
            },
        ];
    }

    private function coverageFit(
        RankablePlanSnapshotData $snapshot,
        InsurancePlan $plan,
        RankablePetProfileData $petProfile,
    ): float {
        $scores = [1.0];
        $targetAudience = $snapshot->targetAudienceSnapshot;
        $coverageRule = $snapshot->coverageRuleSnapshot;
        $ageYears = $this->ageInYears($petProfile->birthDate);
        $ageMonths = $this->ageInMonths($petProfile->birthDate);
        $budget = $petProfile->ownerBudgetMonthly;
        $breed = $petProfile->breed;

        if (($targetAudience['species'] ?? null) !== null && ($targetAudience['species'] ?? []) !== []) {
            $scores[] = $this->containsNormalized((array) $targetAudience['species'], $petProfile->species) ? 1.0 : 0.3;
        } else {
            $scores[] = $this->containsNormalized($plan->species_supported ?? [], $petProfile->species) ? 1.0 : 0.3;
        }

        if (($targetAudience['age_range'] ?? null) !== null && $ageYears !== null) {
            $minMonths = $targetAudience['age_range']['min_months'] ?? null;
            $maxYears = $targetAudience['age_range']['max_years'] ?? null;
            $matchesMin = $minMonths === null || ($ageMonths !== null && $ageMonths >= $minMonths);
            $matchesMax = $maxYears === null || $ageYears <= $maxYears;
            $scores[] = ($matchesMin && $matchesMax) ? 1.0 : 0.4;
        }

        if (($targetAudience['owner_budget_range'] ?? null) !== null && $budget !== null) {
            $min = $targetAudience['owner_budget_range']['min'] ?? null;
            $max = $targetAudience['owner_budget_range']['max'] ?? null;
            $scores[] = ($min === null || $budget >= $min) && ($max === null || $budget <= $max) ? 1.0 : 0.35;
        }

        if (($targetAudience['lifestyle_tags'] ?? null) !== null && ($targetAudience['lifestyle_tags'] ?? []) !== []) {
            $intersection = array_intersect(
                array_map('strtolower', (array) $targetAudience['lifestyle_tags']),
                array_map('strtolower', $petProfile->lifestyleTags),
            );
            $scores[] = round(count($intersection) / count((array) $targetAudience['lifestyle_tags']), 2);
        }

        if ($breed !== null && ($targetAudience['breeds_exclude'] ?? []) !== [] && PetBreedMatcher::matches((array) $targetAudience['breeds_exclude'], $breed)) {
            $scores[] = 0.1;
        } elseif ($breed !== null && ($targetAudience['breeds_include'] ?? null) !== null && ($targetAudience['breeds_include'] ?? []) !== []) {
            $scores[] = PetBreedMatcher::matches((array) $targetAudience['breeds_include'], $breed) ? 1.0 : 0.45;
        }

        if (($coverageRule['waiting_period_days'] ?? null) !== null) {
            $scores[] = (int) $coverageRule['waiting_period_days'] <= 30 ? 1.0 : 0.75;
        }

        return round(max(0.05, min(1.0, array_sum($scores) / count($scores))), 4);
    }

    private function claimability(RankablePlanSnapshotData $snapshot): float
    {
        $claimStrategy = $snapshot->claimStrategySnapshot;
        if ($claimStrategy === []) {
            return 0.75;
        }

        $reimbursementFactor = (float) ($claimStrategy['reimbursement_ratio'] ?? 0);
        $copayFactor = 1 - (float) ($claimStrategy['co_pay_ratio'] ?? 0);
        $deductibleFactor = 1 - min(
            1,
            ((float) ($claimStrategy['deductible_amount'] ?? 0)) / max((float) ($claimStrategy['annual_claim_limit'] ?? 1), 1)
        );
        $windowFactor = min(1, ((int) ($claimStrategy['claim_submission_window_days'] ?? 0)) / 90);
        $preAuthorizationFactor = ((bool) ($claimStrategy['pre_authorization_required'] ?? false)) ? 0.85 : 1.0;

        return round(max(
            0.05,
            min(
                1.0,
                ($reimbursementFactor * 0.45)
                + ($copayFactor * 0.2)
                + ($deductibleFactor * 0.15)
                + ($windowFactor * 0.1)
                + ($preAuthorizationFactor * 0.1)
            )
        ), 4);
    }

    private function weightMultiplier(RankablePlanSnapshotData $snapshot): float
    {
        if ($snapshot->scoringWeightSnapshot === []) {
            return 1.0;
        }

        return round(max(
            0.5,
            (
                ((float) ($snapshot->scoringWeightSnapshot['risk_weight'] ?? 1))
                + ((float) ($snapshot->scoringWeightSnapshot['coverage_weight'] ?? 1))
                + ((float) ($snapshot->scoringWeightSnapshot['claimability_weight'] ?? 1))
            ) / 3
        ), 4);
    }

    private function sponsorMultiplier(RankablePlanSnapshotData $snapshot): float
    {
        return max(1.0, (float) ($snapshot->rankingStrategySnapshot['sponsor_boost'] ?? 1.0));
    }

    private function breakdown(
        int $finalScore,
        int $riskScore,
        float $weightMultiplier,
        float $coverageFit,
        float $claimability,
        float $sponsorMultiplier,
    ): array {
        if ($finalScore === 0) {
            return [
                'risk' => 0,
                'coverage' => 0,
                'claimability' => 0,
                'sponsor' => 0,
            ];
        }

        $raw = [
            'risk' => max($riskScore * $weightMultiplier, 1),
            'coverage' => max($coverageFit * 35, 1),
            'claimability' => max($claimability * 25, 1),
            'sponsor' => max(($sponsorMultiplier - 1.0) * 20, 0),
        ];

        $sum = array_sum($raw);
        $allocated = [];
        $remaining = $finalScore;
        $keys = array_keys($raw);

        foreach ($keys as $index => $key) {
            if ($index === count($keys) - 1) {
                $allocated[$key] = $remaining;
                break;
            }

            $allocated[$key] = (int) floor(($raw[$key] / $sum) * $finalScore);
            $remaining -= $allocated[$key];
        }

        return $allocated;
    }

    private function containsNormalized(array $values, string $needle): bool
    {
        return in_array(strtolower($needle), array_map(fn (mixed $value): string => strtolower((string) $value), $values), true);
    }

    private function ageInYears(?string $birthDate): ?int
    {
        if ($birthDate === null || $birthDate === '') {
            return null;
        }

        return Carbon::parse($birthDate)->age;
    }

    private function ageInMonths(?string $birthDate): ?int
    {
        if ($birthDate === null || $birthDate === '') {
            return null;
        }

        return (int) Carbon::parse($birthDate)
            ->startOfDay()
            ->diffInMonths(now()->copy()->startOfDay());
    }
}
