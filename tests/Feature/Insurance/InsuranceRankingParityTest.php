<?php

namespace Tests\Feature\Insurance;

use App\Models\Activity;
use App\Models\HealthRecord;
use App\Models\InsurancePlan;
use App\Models\InsuranceProvider;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InsuranceRankingParityTest extends TestCase
{
    use RefreshDatabase;

    public function test_consumer_ranking_matches_provider_baseline_fixture(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;
        $pet = Pet::factory()->create([
            'user_id' => $user->id,
            'type' => 'dog',
            'breed' => 'Shiba Inu',
            'birthday' => now()->subYears(4)->toDateString(),
        ]);

        HealthRecord::factory()->create([
            'pet_id' => $pet->id,
            'type' => 'checkup',
            'value' => 'annual health check',
            'recorded_at' => now()->subDays(20),
        ]);

        Activity::query()->create([
            'pet_id' => $pet->id,
            'type' => 'indoor',
            'description' => 'Indoor lifestyle',
            'occurred_at' => now()->subDay(),
        ]);

        $provider = InsuranceProvider::query()->create([
            'source_provider_id' => 10,
            'name' => 'Alpha Pet Insurance',
        ]);

        $this->createParityPlan(
            provider: $provider,
            sourcePlanId: 1001,
            name: 'Alpha Dog Plan',
            sponsorBoost: 1.1,
            exposurePriority: 80,
            waitingPeriodDays: 30,
            deductibleAmount: 3000,
            reimbursementRatio: 0.8,
            annualClaimLimit: 120000,
            copayRatio: 0.1,
            claimWindowDays: 90,
            preAuthorizationRequired: false,
            coverageWeight: 1.0,
            claimabilityWeight: 0.9,
        );

        $this->createParityPlan(
            provider: $provider,
            sourcePlanId: 1002,
            name: 'Beta Dog Plan',
            sponsorBoost: 1.0,
            exposurePriority: 40,
            waitingPeriodDays: 45,
            deductibleAmount: 5000,
            reimbursementRatio: 0.75,
            annualClaimLimit: 100000,
            copayRatio: 0.2,
            claimWindowDays: 60,
            preAuthorizationRequired: true,
            coverageWeight: 0.8,
            claimabilityWeight: 0.85,
            coverageItems: ['accident', 'illness'],
        );

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson("/api/pets/{$pet->id}/insurance/plans");

        $response->assertOk()
            ->assertJsonPath('data.plans.0.name', 'Alpha Dog Plan')
            ->assertJsonPath('data.plans.0.final_score', 26)
            ->assertJsonPath('data.plans.0.ranking_position', 1)
            ->assertJsonPath('data.plans.0.score_breakdown.risk', 8)
            ->assertJsonPath('data.plans.0.score_breakdown.coverage', 10)
            ->assertJsonPath('data.plans.0.score_breakdown.claimability', 6)
            ->assertJsonPath('data.plans.0.score_breakdown.sponsor', 2)
            ->assertJsonPath('data.plans.1.name', 'Beta Dog Plan')
            ->assertJsonPath('data.plans.1.final_score', 19)
            ->assertJsonPath('data.plans.1.ranking_position', 2)
            ->assertJsonPath('data.plans.1.score_breakdown.risk', 6)
            ->assertJsonPath('data.plans.1.score_breakdown.coverage', 8)
            ->assertJsonPath('data.plans.1.score_breakdown.claimability', 4)
            ->assertJsonPath('data.plans.1.score_breakdown.sponsor', 1)
            ->assertJsonPath('data.meta.algorithm_version', 'v1');
    }

    private function createParityPlan(
        InsuranceProvider $provider,
        int $sourcePlanId,
        string $name,
        float $sponsorBoost,
        int $exposurePriority,
        int $waitingPeriodDays,
        float $deductibleAmount,
        float $reimbursementRatio,
        float $annualClaimLimit,
        float $copayRatio,
        int $claimWindowDays,
        bool $preAuthorizationRequired,
        float $coverageWeight,
        float $claimabilityWeight,
        array $coverageItems = ['accident', 'illness', 'surgery', 'liability'],
    ): InsurancePlan {
        return InsurancePlan::query()->create([
            'insurance_provider_id' => $provider->id,
            'source_provider_id' => $provider->source_provider_id,
            'source_plan_id' => $sourcePlanId,
            'code' => 'PLAN-'.$sourcePlanId,
            'name' => $name,
            'summary' => 'Parity fixture plan',
            'plan_type' => 'comprehensive',
            'currency' => 'TWD',
            'annual_premium_min' => 3200,
            'annual_premium_max' => 7800,
            'species_supported' => ['dog'],
            'scoring_weight_snapshot' => [
                'risk_weight' => 1.2,
                'coverage_weight' => $coverageWeight,
                'claimability_weight' => $claimabilityWeight,
                'component_weights' => ['age' => 0.2, 'health' => 0.8],
            ],
            'coverage_rule_snapshot' => [
                'eligible_species' => ['dog'],
                'eligible_breeds' => ['Shiba Inu'],
                'min_age_months' => 2,
                'max_age_years' => $sourcePlanId === 1001 ? 10 : 12,
                'waiting_period_days' => $waitingPeriodDays,
                'excluded_conditions' => ['pre_existing_conditions'],
                'coverage_items' => $coverageItems,
            ],
            'claim_strategy_snapshot' => [
                'deductible_amount' => $deductibleAmount,
                'reimbursement_ratio' => $reimbursementRatio,
                'annual_claim_limit' => $annualClaimLimit,
                'co_pay_ratio' => $copayRatio,
                'claim_submission_window_days' => $claimWindowDays,
                'pre_authorization_required' => $preAuthorizationRequired,
            ],
            'target_audience_snapshot' => [
                'species' => ['dog'],
                'breeds_include' => ['Shiba Inu'],
                'breeds_exclude' => [],
                'age_range' => ['min_months' => 2, 'max_years' => $sourcePlanId === 1001 ? 8 : 10],
                'owner_budget_range' => ['min' => 1000, 'max' => $sourcePlanId === 1001 ? 3000 : 2500],
                'lifestyle_tags' => ['indoor'],
            ],
            'ranking_strategy_snapshot' => [
                'sponsor_boost' => $sponsorBoost,
                'exposure_priority' => $exposurePriority,
                'tie_breakers' => ['claimability', 'updated_at'],
            ],
            'eligibility_snapshot' => [
                'species_supported' => ['dog'],
                'breed_rules' => ['Shiba Inu'],
                'min_age_months' => 2,
                'max_age_years' => $sourcePlanId === 1001 ? 10 : 12,
                'requires_microchip' => true,
                'requires_registration' => true,
                'excluded_pet_usages' => [],
            ],
            'coverage_summary_snapshot' => [
                'medical' => [
                    'enabled' => true,
                    'outpatient' => true,
                    'hospitalization' => true,
                    'surgery' => in_array('surgery', $coverageItems, true),
                ],
                'liability' => ['enabled' => in_array('liability', $coverageItems, true)],
                'lost_pet_ad' => ['enabled' => false],
                'owner_hospital_boarding' => ['enabled' => false],
                'funeral' => ['enabled' => false],
                'reacquisition' => ['enabled' => false],
            ],
            'comparison_snapshot' => [
                'coverage_flags' => [
                    'medical' => true,
                    'liability' => in_array('liability', $coverageItems, true),
                ],
                'waiting_period_days' => [
                    'major_conditions' => null,
                    'general_conditions' => $waitingPeriodDays,
                ],
                'medical_constraints' => [
                    'designated_vet_required' => false,
                    'skin_hair_outpatient_only' => false,
                    'same_incident_window_days' => null,
                    'same_incident_max_claim_count' => null,
                ],
                'common_exclusions' => ['pre_existing_conditions'],
            ],
            'claim_requirement_snapshot' => [
                'diagnosis_with_chip_id' => true,
                'original_receipt_required' => true,
                'digital_records_supported' => true,
                'referral_rule_required' => false,
            ],
            'source_status' => 'active',
            'algorithm_version' => 'v1',
            'is_listable' => true,
            'source_updated_at' => now(),
            'first_synced_at' => now(),
            'synced_at' => now(),
            'last_seen_at' => now(),
        ]);
    }
}
