<?php

namespace Tests\Feature;

use App\Models\Affiliate;
use App\Models\InsuranceProfile;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AffiliateTest extends TestCase
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
        ]);
        InsuranceProfile::create([
            'pet_id' => $this->pet->id,
            'risk_score' => 90,
            'premium_discount' => 2.00,
            'last_calculated_at' => now(),
        ]);
    }

    private function auth(): array
    {
        return ['Authorization' => "Bearer {$this->token}"];
    }

    public function test_can_list_all_active_affiliates_without_auth(): void
    {
        Affiliate::factory()->count(3)->create(['is_active' => true]);
        Affiliate::factory()->create(['is_active' => false]);

        $response = $this->getJson('/api/affiliates');

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonCount(3, 'data');
    }

    public function test_can_get_pet_affiliate_recommendations(): void
    {
        // Affiliate that matches dog + risk 90
        Affiliate::create([
            'name' => 'Dog Insurance',
            'affiliate_url' => 'https://dog.ins',
            'is_active' => true,
            'target_pet_types' => ['dog'],
            'min_risk_score' => 0,
            'max_risk_score' => 120,
            'commission_rate' => 8.00,
        ]);
        // Affiliate for high risk only — should NOT match
        Affiliate::create([
            'name' => 'High Risk Only',
            'affiliate_url' => 'https://high.ins',
            'is_active' => true,
            'target_pet_types' => ['dog'],
            'min_risk_score' => 130,
            'max_risk_score' => 200,
            'commission_rate' => 5.00,
        ]);

        $response = $this->withHeaders($this->auth())
            ->getJson("/api/pets/{$this->pet->id}/affiliates");

        $response->assertStatus(200)
            ->assertJsonPath('data.pet.id', $this->pet->id)
            ->assertJsonStructure(['data' => ['pet', 'insurance_profile', 'recommendations']]);

        // Only 1 recommendation should match (risk 90 < 120)
        $this->assertCount(1, $response->json('data.recommendations'));
    }

    public function test_cannot_get_recommendations_for_other_users_pet(): void
    {
        $otherPet = Pet::factory()->create(['user_id' => User::factory()->create()->id]);

        $response = $this->withHeaders($this->auth())
            ->getJson("/api/pets/{$otherPet->id}/affiliates");

        $response->assertStatus(403);
    }
}
