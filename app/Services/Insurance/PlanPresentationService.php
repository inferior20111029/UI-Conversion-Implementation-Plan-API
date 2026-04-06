<?php

namespace App\Services\Insurance;

use App\Models\InsurancePlan;

class PlanPresentationService
{
    public function listItem(InsurancePlan $plan, array $ranking): array
    {
        return [
            'id' => $plan->id,
            'provider_name' => $plan->provider?->name,
            'name' => $plan->name,
            'summary' => $plan->summary,
            'currency' => $plan->currency,
            'annual_premium_min' => $plan->annual_premium_min,
            'annual_premium_max' => $plan->annual_premium_max,
            'species_supported' => $plan->species_supported ?? [],
            'final_score' => $ranking['final_score'],
            'ranking_position' => $ranking['ranking_position'],
            'coverage_fit' => $ranking['coverage_fit'],
            'claimability' => $ranking['claimability'],
            'sponsor_boost' => $ranking['sponsor_boost'],
            'score_breakdown' => $ranking['breakdown'] ?? [],
            'badges' => $this->badges($plan),
            'why_recommended' => $this->whyRecommended($plan, $ranking),
        ];
    }

    public function detail(InsurancePlan $plan, ?array $ranking = null): array
    {
        $coverageSummary = $plan->coverage_summary_snapshot;
        $comparison = $plan->comparison_snapshot;
        $claimRequirements = $plan->claim_requirement_snapshot;
        $enabledFlags = $coverageSummary->enabledFlags();

        return [
            'id' => $plan->id,
            'provider' => [
                'id' => $plan->insurance_provider_id,
                'name' => $plan->provider?->name,
            ],
            'plan' => [
                'name' => $plan->name,
                'code' => $plan->code,
                'summary' => $plan->summary,
            ],
            'pricing' => [
                'currency' => $plan->currency,
                'annual_premium_min' => $plan->annual_premium_min,
                'annual_premium_max' => $plan->annual_premium_max,
            ],
            'eligibility' => $plan->eligibility_snapshot->toArray(),
            'coverage' => $enabledFlags,
            'waiting_period' => [
                'major_conditions_days' => $comparison->waitingPeriodDays['major_conditions'] ?? null,
                'general_conditions_days' => $comparison->waitingPeriodDays['general_conditions'] ?? null,
            ],
            'medical_constraints' => $comparison->medicalConstraints,
            'exclusions' => $comparison->commonExclusions,
            'claim_requirements' => $claimRequirements->toArray(),
            'score_breakdown' => $ranking['breakdown'] ?? null,
            'badges' => $this->badges($plan),
            'why_recommended' => $this->whyRecommended($plan, $ranking),
            'terms' => [
                'url' => $plan->terms_url,
                'source_updated_at' => $plan->source_updated_at?->toISOString(),
            ],
            'algorithm_version' => $ranking['algorithm_version'] ?? $plan->algorithm_version,
        ];
    }

    public function badges(InsurancePlan $plan): array
    {
        $badges = [];
        $eligibility = $plan->eligibility_snapshot;
        $comparison = $plan->comparison_snapshot;
        $coverageFlags = $plan->coverage_summary_snapshot->enabledFlags();

        $species = array_map('strtoupper', $eligibility->speciesSupported ?: ($plan->species_supported ?? []));
        if (in_array('DOG', $species, true) && in_array('CAT', $species, true)) {
            $badges[] = '犬貓適用';
        } elseif ($species !== []) {
            $badges[] = implode(' / ', array_map(fn (string $value): string => $value === 'DOG' ? '犬' : ($value === 'CAT' ? '貓' : $value), $species)).'適用';
        }

        $generalWaiting = $comparison->waitingPeriodDays['general_conditions'] ?? null;
        $majorWaiting = $comparison->waitingPeriodDays['major_conditions'] ?? null;
        if ($generalWaiting !== null || $majorWaiting !== null) {
            $badges[] = sprintf(
                '等待期%s',
                $majorWaiting !== null && $generalWaiting !== null
                    ? "{$generalWaiting}/{$majorWaiting}天"
                    : (($generalWaiting ?? $majorWaiting).'天')
            );
        }

        if (($comparison->medicalConstraints['designated_vet_required'] ?? false) === true) {
            $badges[] = '指定獸醫院';
        }

        if (($coverageFlags['liability'] ?? false) === true) {
            $badges[] = '含侵權責任';
        }

        if (($coverageFlags['funeral'] ?? false) === true) {
            $badges[] = '含喪葬';
        }

        return array_values(array_slice(array_unique($badges), 0, 5));
    }

    public function whyRecommended(InsurancePlan $plan, ?array $ranking = null): array
    {
        $messages = [];
        $coverageFlags = $plan->coverage_summary_snapshot->enabledFlags();
        $claimRequirements = array_filter($plan->claim_requirement_snapshot->toArray(), fn (mixed $value): bool => $value !== null);

        if ($ranking !== null && ($ranking['eligibility']['eligible'] ?? false)) {
            $messages[] = '符合寵物年齡與物種條件';
        }

        if (count(array_filter($coverageFlags)) >= 2) {
            $messages[] = '保障內容完整';
        }

        if (count($claimRequirements) >= 2) {
            $messages[] = '理賠資料要求明確';
        }

        if ($ranking !== null && ($ranking['sponsor_boost'] ?? 0) > 0) {
            $messages[] = '具備商業曝光加權';
        }

        return array_values(array_slice(array_unique($messages), 0, 3));
    }
}
