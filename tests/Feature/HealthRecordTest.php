<?php

namespace Tests\Feature;

use App\Models\HealthRecord;
use App\Models\InsuranceProfile;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HealthRecordTest extends TestCase
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
        // Set a young pet (< 5 years) so age doesn't add risk penalty
        $this->pet = Pet::factory()->create([
            'user_id' => $this->user->id,
            'birthday' => now()->subYears(2)->format('Y-m-d'),
        ]);
        InsuranceProfile::create([
            'pet_id' => $this->pet->id,
            'risk_score' => 100,
            'premium_discount' => 0,
            'last_calculated_at' => now(),
        ]);
    }

    private function auth(): array
    {
        return ['Authorization' => "Bearer {$this->token}"];
    }

    public function test_can_create_health_record_and_risk_is_recalculated(): void
    {
        $response = $this->withHeaders($this->auth())
            ->postJson("/api/pets/{$this->pet->id}/records", [
                'type' => 'vaccine',
                'value' => json_encode(['name' => 'Rabies']),
                'recorded_at' => now()->toDateString(),
            ]);

        $response->assertStatus(201)
            ->assertJson(['success' => true])
            ->assertJsonStructure(['data' => ['record', 'insurance_profile']]);

        // Risk score should be recalculated
        $this->assertDatabaseHas('insurance_profiles', ['pet_id' => $this->pet->id]);
        $profile = InsuranceProfile::where('pet_id', $this->pet->id)->first();
        // Young pet with a recent vaccine: base 100, no age penalty, recent vaccine -10 = 90
        $this->assertEquals(90, $profile->risk_score);
        $this->assertGreaterThan(0, $profile->premium_discount);
    }

    public function test_can_list_health_records_for_own_pet(): void
    {
        HealthRecord::factory()->count(3)->create(['pet_id' => $this->pet->id]);

        $response = $this->withHeaders($this->auth())
            ->getJson("/api/pets/{$this->pet->id}/records");

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_cannot_add_record_to_other_users_pet(): void
    {
        $otherPet = Pet::factory()->create(['user_id' => User::factory()->create()->id]);

        $response = $this->withHeaders($this->auth())
            ->postJson("/api/pets/{$otherPet->id}/records", [
                'type' => 'weight',
                'value' => '25kg',
            ]);

        $response->assertStatus(403);
    }

    public function test_health_record_type_validation(): void
    {
        $response = $this->withHeaders($this->auth())
            ->postJson("/api/pets/{$this->pet->id}/records", [
                'type' => 'invalid_type',
            ]);

        $response->assertStatus(422);
    }

    public function test_can_delete_health_record(): void
    {
        $record = HealthRecord::factory()->create(['pet_id' => $this->pet->id]);

        $response = $this->withHeaders($this->auth())
            ->deleteJson("/api/records/{$record->id}");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('health_records', ['id' => $record->id]);
    }
}
