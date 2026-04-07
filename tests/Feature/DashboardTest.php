<?php

namespace Tests\Feature;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_response_includes_pet_insurance_type_mapping(): void
    {
        $user = User::factory()->create();
        $pet = Pet::factory()->create([
            'user_id' => $user->id,
            'name' => 'Whiskers',
            'type' => 'cat',
            'breed' => 'Ragdoll',
            'microchip_number' => 'CAT-0001',
            'is_registered' => true,
            'registration_number' => 'CAT-REG-001',
        ]);

        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson("/api/dashboard/pets/{$pet->id}");

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $pet->id)
            ->assertJsonPath('data.type', 'cat')
            ->assertJsonPath('data.type_label', '貓')
            ->assertJsonPath('data.breed', 'Ragdoll')
            ->assertJsonPath('data.microchip_number', 'CAT-0001')
            ->assertJsonPath('data.has_microchip', true)
            ->assertJsonPath('data.is_registered', true)
            ->assertJsonPath('data.registration_number', 'CAT-REG-001')
            ->assertJsonPath('data.insurance_type.key', 'cat_insurance')
            ->assertJsonPath('data.insurance_type.label', '貓用保險')
            ->assertJsonPath('data.health_summary.total_items_count', 6)
            ->assertJsonPath('data.activity_summary.minutes_last_7_days', 0)
            ->assertJsonPath('data.preventive_care.checkup.status', 'missing')
            ->assertJsonPath('data.preventive_care.vaccine.status', 'missing')
            ->assertJsonPath('data.recommended_plan.name', 'Whiskers 專屬守護方案')
            ->assertJsonStructure([
                'success',
                'data' => [
                    'risk_profile' => [
                        'insights' => [
                            '*' => [
                                'key',
                                'title',
                                'current_status',
                                'status_type',
                                'recommendation',
                                'financial_impact' => [
                                    'type',
                                    'amount',
                                    'unit',
                                    'label',
                                ],
                            ],
                        ],
                    ],
                    'health_summary' => [
                        'completion_percent',
                        'completed_items_count',
                        'total_items_count',
                        'items',
                        'missing_items',
                    ],
                    'latest_weight' => [
                        'value_kg',
                        'recorded_at',
                        'delta_kg',
                        'progress',
                        'note',
                    ],
                    'recommended_plan' => [
                        'id',
                        'provider_name',
                        'name',
                        'currency',
                        'market_price_monthly',
                        'discounted_price_monthly',
                        'monthly_savings',
                        'ranking_position',
                        'final_score',
                        'badges',
                        'why_recommended',
                        'next_milestone' => [
                            'title',
                            'progress_percent',
                            'projected_price_monthly',
                            'target_discount_percent',
                            'helper_text',
                        ],
                    ],
                ],
            ]);
    }
}
