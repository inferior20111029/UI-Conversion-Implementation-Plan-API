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
            ->assertJsonPath('data.insurance_type.label', '貓用保險');
    }
}
