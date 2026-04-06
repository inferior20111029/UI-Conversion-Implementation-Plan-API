<?php

namespace Tests\Feature;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test')->plainTextToken;
    }

    private function auth(): array
    {
        return ['Authorization' => "Bearer {$this->token}"];
    }

    public function test_user_can_list_own_pets(): void
    {
        Pet::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders($this->auth())
            ->getJson('/api/pets');

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonCount(3, 'data');
    }

    public function test_user_can_create_pet(): void
    {
        $response = $this->withHeaders($this->auth())
            ->postJson('/api/pets', [
                'name' => 'Buddy',
                'type' => 'dog',
                'breed' => 'Labrador',
                'birthday' => '2020-05-01',
                'microchip_number' => 'mcp-123456789',
                'is_registered' => true,
                'registration_number' => 'reg-0001',
            ]);

        $response->assertStatus(201)
            ->assertJson(['success' => true])
            ->assertJsonPath('data.name', 'Buddy')
            ->assertJsonPath('data.type_label', '狗')
            ->assertJsonPath('data.insurance_type.key', 'dog_insurance')
            ->assertJsonPath('data.microchip_number', 'MCP-123456789')
            ->assertJsonPath('data.has_microchip', true)
            ->assertJsonPath('data.is_registered', true)
            ->assertJsonPath('data.registration_number', 'REG-0001');

        $this->assertDatabaseHas('pets', ['name' => 'Buddy', 'user_id' => $this->user->id]);
        // Insurance Profile auto-created
        $this->assertDatabaseHas('insurance_profiles', []);
    }

    public function test_create_pet_validation_fails_with_invalid_type(): void
    {
        $response = $this->withHeaders($this->auth())
            ->postJson('/api/pets', [
                'name' => 'Buddy',
                'type' => 'fish', // invalid
            ]);

        $response->assertStatus(422);
    }

    public function test_user_can_view_own_pet(): void
    {
        $pet = Pet::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders($this->auth())
            ->getJson("/api/pets/{$pet->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $pet->id);
    }

    public function test_user_cannot_view_other_users_pet(): void
    {
        $otherUser = User::factory()->create();
        $pet = Pet::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->withHeaders($this->auth())
            ->getJson("/api/pets/{$pet->id}");

        $response->assertStatus(403);
    }

    public function test_user_can_update_pet(): void
    {
        $pet = Pet::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders($this->auth())
            ->putJson("/api/pets/{$pet->id}", [
                'name' => 'Max Updated',
                'microchip_number' => 'chip-0001',
                'is_registered' => true,
                'registration_number' => 'reg-2026-01',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Max Updated')
            ->assertJsonPath('data.microchip_number', 'CHIP-0001')
            ->assertJsonPath('data.has_microchip', true)
            ->assertJsonPath('data.is_registered', true)
            ->assertJsonPath('data.registration_number', 'REG-2026-01');
    }

    public function test_updating_pet_type_updates_insurance_type_mapping(): void
    {
        $pet = Pet::factory()->create([
            'user_id' => $this->user->id,
            'type' => 'dog',
        ]);

        $response = $this->withHeaders($this->auth())
            ->putJson("/api/pets/{$pet->id}", ['type' => 'cat']);

        $response->assertStatus(200)
            ->assertJsonPath('data.type', 'cat')
            ->assertJsonPath('data.type_label', '貓')
            ->assertJsonPath('data.insurance_type.key', 'cat_insurance')
            ->assertJsonPath('data.insurance_type.label', '貓用保險');
    }

    public function test_user_can_delete_pet(): void
    {
        $pet = Pet::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders($this->auth())
            ->deleteJson("/api/pets/{$pet->id}");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('pets', ['id' => $pet->id]);
    }

    public function test_unauthenticated_cannot_access_pets(): void
    {
        $response = $this->getJson('/api/pets');

        $response->assertStatus(401);
    }
}
