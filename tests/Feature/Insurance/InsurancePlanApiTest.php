<?php

namespace Tests\Feature\Insurance;

use App\Models\HealthRecord;
use App\Models\InsurancePlan;
use App\Models\InsuranceProvider;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InsurancePlanApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Pet $pet;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test')->plainTextToken;
        $this->pet = Pet::factory()->create([
            'user_id' => $this->user->id,
            'type' => 'dog',
            'breed' => 'Shiba-Inu',
            'birthday' => now()->subYears(4)->toDateString(),
            'microchip_number' => 'DOG-123456',
            'is_registered' => true,
            'registration_number' => 'DOG-REG-123456',
        ]);

        HealthRecord::factory()->create([
            'pet_id' => $this->pet->id,
            'type' => 'checkup',
            'value' => 'annual health check',
            'recorded_at' => now()->subDays(20),
        ]);
    }

    public function test_pet_insurance_list_returns_ranked_provider_plans(): void
    {
        $provider = InsuranceProvider::query()->create([
            'source_provider_id' => 10,
            'name' => 'Alpha Pet Insurance',
        ]);

        $eligiblePlan = $this->createInsurancePlan($provider, 101, 'Alpha Dog Plan', ['dog']);
        $this->createInsurancePlan($provider, 102, 'Cat Only Plan', ['cat']);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson("/api/pets/{$this->pet->id}/insurance/plans");

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data.plans')
            ->assertJsonPath('data.plans.0.id', $eligiblePlan->id)
            ->assertJsonPath('data.plans.0.provider_name', 'Alpha Pet Insurance')
            ->assertJsonPath('data.plans.0.badges.0', '犬適用')
            ->assertJsonPath('data.plans.0.why_recommended.1', '符合品種條件')
            ->assertJsonPath('data.plans.0.why_recommended.2', '命中保險公司偏好品種')
            ->assertJsonPath('data.meta.pet.breed', 'Shiba-Inu')
            ->assertJsonPath('data.meta.pet.has_microchip', true)
            ->assertJsonPath('data.meta.pet.is_registered', true)
            ->assertJsonPath('data.meta.pet.insurance_type.key', 'dog_insurance')
            ->assertJsonPath('data.meta.total', 1);
    }

    public function test_plan_requiring_microchip_is_filtered_out_when_pet_has_no_microchip(): void
    {
        $provider = InsuranceProvider::query()->create([
            'source_provider_id' => 11,
            'name' => 'Beta Pet Insurance',
        ]);

        $petWithoutChip = Pet::factory()->create([
            'user_id' => $this->user->id,
            'type' => 'dog',
            'breed' => 'Shiba Inu',
            'birthday' => now()->subYears(3)->toDateString(),
            'microchip_number' => null,
            'is_registered' => true,
            'registration_number' => 'DOG-REG-999',
        ]);

        $this->createInsurancePlan($provider, 103, 'Chip Required Plan', ['dog']);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson("/api/pets/{$petWithoutChip->id}/insurance/plans");

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(0, 'data.plans')
            ->assertJsonPath('data.meta.pet.has_microchip', false);
    }

    public function test_plan_requiring_registration_is_filtered_out_when_pet_is_not_registered(): void
    {
        $provider = InsuranceProvider::query()->create([
            'source_provider_id' => 12,
            'name' => 'Gamma Pet Insurance',
        ]);

        $petWithoutRegistration = Pet::factory()->create([
            'user_id' => $this->user->id,
            'type' => 'dog',
            'breed' => 'Shiba Inu',
            'birthday' => now()->subYears(3)->toDateString(),
            'microchip_number' => 'DOG-333333',
            'is_registered' => false,
            'registration_number' => null,
        ]);

        $this->createInsurancePlan($provider, 104, 'Registration Required Plan', ['dog']);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson("/api/pets/{$petWithoutRegistration->id}/insurance/plans");

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(0, 'data.plans')
            ->assertJsonPath('data.meta.pet.is_registered', false);
    }

    public function test_plan_detail_returns_structured_sections_and_score_breakdown(): void
    {
        $provider = InsuranceProvider::query()->create([
            'source_provider_id' => 10,
            'name' => 'Alpha Pet Insurance',
        ]);

        $plan = $this->createInsurancePlan($provider, 101, 'Alpha Dog Plan', ['dog']);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson("/api/insurance/plans/{$plan->id}?pet_id={$this->pet->id}");

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.provider.name', 'Alpha Pet Insurance')
            ->assertJsonPath('data.plan.name', 'Alpha Dog Plan')
            ->assertJsonPath('data.coverage.liability', true)
            ->assertJsonPath('data.claim_requirements.original_receipt_required', true)
            ->assertJsonPath('data.algorithm_version', 'v1');

        $this->assertNotNull($response->json('data.score_breakdown'));
    }

    private function createInsurancePlan(InsuranceProvider $provider, int $sourcePlanId, string $name, array $species): InsurancePlan
    {
        return InsurancePlan::query()->create([
            'insurance_provider_id' => $provider->id,
            'source_provider_id' => $provider->source_provider_id,
            'source_plan_id' => $sourcePlanId,
            'code' => 'PLAN-'.$sourcePlanId,
            'name' => $name,
            'summary' => 'Comprehensive plan',
            'plan_type' => 'comprehensive',
            'currency' => 'TWD',
            'annual_premium_min' => 3200,
            'annual_premium_max' => 7800,
            'species_supported' => $species,
            'terms_url' => 'https://provider.test/terms',
            'effective_from' => now()->subDay()->toDateString(),
            'effective_to' => now()->addYear()->toDateString(),
            'scoring_weight_snapshot' => [
                'risk_weight' => 1.2,
                'coverage_weight' => 1.0,
                'claimability_weight' => 0.9,
            ],
            'coverage_rule_snapshot' => [
                'eligible_species' => $species,
                'eligible_breeds' => ['Shiba Inu'],
                'min_age_months' => 2,
                'max_age_years' => 10,
                'waiting_period_days' => 30,
                'excluded_conditions' => ['pre_existing_conditions'],
                'coverage_items' => ['accident', 'illness', 'surgery', 'liability'],
            ],
            'claim_strategy_snapshot' => [
                'deductible_amount' => 3000,
                'reimbursement_ratio' => 0.8,
                'annual_claim_limit' => 120000,
                'co_pay_ratio' => 0.1,
                'claim_submission_window_days' => 90,
                'pre_authorization_required' => false,
            ],
            'target_audience_snapshot' => [
                'species' => $species,
                'breeds_include' => ['Shiba Inu'],
                'breeds_exclude' => [],
                'age_range' => ['min_months' => 2, 'max_years' => 8],
                'owner_budget_range' => ['min' => 1000, 'max' => 3000],
                'lifestyle_tags' => ['indoor'],
            ],
            'ranking_strategy_snapshot' => [
                'sponsor_boost' => 1.1,
                'exposure_priority' => 80,
            ],
            'eligibility_snapshot' => [
                'species_supported' => $species,
                'breed_rules' => ['Shiba Inu'],
                'min_age_months' => 2,
                'max_age_years' => 10,
                'requires_microchip' => true,
                'requires_registration' => true,
                'excluded_pet_usages' => [],
            ],
            'coverage_summary_snapshot' => [
                'medical' => ['enabled' => true, 'outpatient' => true, 'hospitalization' => true, 'surgery' => true],
                'liability' => ['enabled' => true],
                'lost_pet_ad' => ['enabled' => false],
                'owner_hospital_boarding' => ['enabled' => false],
                'funeral' => ['enabled' => true],
                'reacquisition' => ['enabled' => false],
            ],
            'comparison_snapshot' => [
                'coverage_flags' => ['medical' => true, 'liability' => true, 'funeral' => true],
                'waiting_period_days' => ['major_conditions' => 90, 'general_conditions' => 30],
                'medical_constraints' => ['designated_vet_required' => true],
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
