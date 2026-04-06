<?php

namespace Tests\Feature\Console;

use App\Models\InsurancePlan;
use App\Models\InsuranceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class InsuranceCatalogSyncCommandTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.provider_catalog.base_url', 'https://provider.test/api/internal/v1');
        config()->set('services.provider_catalog.token', 'sync-secret');
    }

    public function test_sync_command_upserts_catalog_records(): void
    {
        $provider = InsuranceProvider::query()->create([
            'source_provider_id' => 10,
            'name' => 'Alpha Pet Insurance',
        ]);

        InsurancePlan::query()->create([
            'insurance_provider_id' => $provider->id,
            'source_provider_id' => 10,
            'source_plan_id' => 101,
            'code' => 'DOG-101',
            'name' => 'Alpha Dog Plan',
            'currency' => 'TWD',
            'annual_premium_min' => 3000,
            'annual_premium_max' => 5000,
            'species_supported' => ['dog'],
            'source_status' => 'active',
            'algorithm_version' => 'v1',
            'is_listable' => true,
            'source_updated_at' => '2026-04-06T09:00:00Z',
            'first_synced_at' => now(),
            'synced_at' => now(),
            'last_seen_at' => now(),
        ]);

        Http::fake([
            'https://provider.test/api/internal/v1/catalog/plans*' => Http::response([
                'status' => 'success',
                'message' => 'Catalog plans exported successfully.',
                'data' => [
                    $this->catalogPlanPayload(name: 'Alpha Dog Plan Plus', premiumMin: 3500, premiumMax: 8000, updatedAt: '2026-04-06T10:00:00Z'),
                ],
                'meta' => [
                    'current_page' => 1,
                    'per_page' => 100,
                    'total' => 1,
                    'last_page' => 1,
                    'algorithm_version' => 'v1',
                    'catalog_generated_at' => '2026-04-06T10:00:00Z',
                ],
            ], 200),
        ]);

        $this->artisan('insurance-catalog:sync')->assertExitCode(0);

        $this->assertDatabaseCount('insurance_plans', 1);
        $this->assertDatabaseHas('insurance_plans', [
            'source_plan_id' => 101,
            'name' => 'Alpha Dog Plan Plus',
        ]);
        $this->assertDatabaseHas('insurance_providers', [
            'source_provider_id' => 10,
            'name' => 'Alpha Pet Insurance',
        ]);
    }

    public function test_full_sync_reconciles_missing_plans(): void
    {
        $provider = InsuranceProvider::query()->create([
            'source_provider_id' => 10,
            'name' => 'Alpha Pet Insurance',
        ]);

        InsurancePlan::query()->create([
            'insurance_provider_id' => $provider->id,
            'source_provider_id' => 10,
            'source_plan_id' => 101,
            'code' => 'DOG-101',
            'name' => 'Old Plan',
            'currency' => 'TWD',
            'annual_premium_min' => 3000,
            'annual_premium_max' => 5000,
            'species_supported' => ['dog'],
            'source_status' => 'active',
            'is_listable' => true,
        ]);

        Http::fake([
            'https://provider.test/api/internal/v1/catalog/plans*' => Http::response([
                'status' => 'success',
                'message' => 'Catalog plans exported successfully.',
                'data' => [],
                'meta' => [
                    'current_page' => 1,
                    'per_page' => 100,
                    'total' => 0,
                    'last_page' => 1,
                    'algorithm_version' => 'v1',
                    'catalog_generated_at' => '2026-04-06T10:00:00Z',
                ],
            ], 200),
        ]);

        $this->artisan('insurance-catalog:sync --full')->assertExitCode(0);

        $this->assertDatabaseHas('insurance_plans', [
            'source_plan_id' => 101,
            'source_status' => 'inactive',
            'is_listable' => 0,
        ]);
    }

    private function catalogPlanPayload(string $name, float $premiumMin, float $premiumMax, string $updatedAt = '2026-04-06T09:00:00Z'): array
    {
        return [
            'source_provider_id' => 10,
            'provider_name' => 'Alpha Pet Insurance',
            'source_plan_id' => 101,
            'code' => 'DOG-101',
            'name' => $name,
            'summary' => 'Comprehensive dog protection.',
            'plan_type' => 'comprehensive',
            'currency' => 'TWD',
            'annual_premium_min' => $premiumMin,
            'annual_premium_max' => $premiumMax,
            'species_supported' => ['dog'],
            'terms_url' => 'https://provider.test/terms',
            'effective_from' => '2026-04-01',
            'effective_to' => '2027-03-31',
            'scoring_weight' => [
                'risk_weight' => 1.2,
                'coverage_weight' => 1.0,
                'claimability_weight' => 0.9,
                'component_weights' => ['age' => 0.2, 'health' => 0.8],
                'normalization_rules' => ['scale' => 100],
                'version' => '2026.04.v1',
            ],
            'coverage_rule' => [
                'eligible_species' => ['dog'],
                'eligible_breeds' => ['Shiba Inu'],
                'min_age_months' => 2,
                'max_age_years' => 10,
                'waiting_period_days' => 30,
                'excluded_conditions' => ['pre_existing_conditions'],
                'coverage_items' => ['accident', 'illness', 'surgery', 'liability'],
                'rule_payload' => [],
            ],
            'claim_strategy' => [
                'deductible_amount' => 3000,
                'reimbursement_ratio' => 0.8,
                'annual_claim_limit' => 120000,
                'co_pay_ratio' => 0.1,
                'claim_submission_window_days' => 90,
                'pre_authorization_required' => false,
                'excluded_claim_types' => ['cosmetic'],
                'claimability_rules' => [],
            ],
            'target_audience' => [
                'species' => ['dog'],
                'breeds_include' => ['Shiba Inu'],
                'breeds_exclude' => [],
                'age_range' => ['min_months' => 2, 'max_years' => 8],
                'owner_budget_range' => ['min' => 1000, 'max' => 3000],
                'geo_regions' => ['TW-NORTH'],
                'lifestyle_tags' => ['indoor', 'active'],
                'priority_conditions' => ['accident_protection'],
            ],
            'ranking_strategy' => [
                'sponsor_boost' => 1.1,
                'exposure_priority' => 80,
                'tie_breakers' => ['claimability', 'updated_at'],
                'distribution_caps' => ['daily_impression_limit' => 1000],
                'featured' => true,
                'active_from' => null,
                'active_to' => null,
            ],
            'eligibility_snapshot' => [
                'species_supported' => ['dog'],
                'breed_rules' => ['Shiba Inu'],
                'min_age_months' => 2,
                'max_age_years' => 10,
                'renewable_max_age_years' => null,
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
            'status' => 'active',
            'source_updated_at' => $updatedAt,
            'algorithm_version' => 'v1',
        ];
    }
}
