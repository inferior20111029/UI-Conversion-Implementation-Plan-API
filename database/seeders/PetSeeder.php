<?php

namespace Database\Seeders;

use App\Models\Pet;
use App\Models\User;
use App\Models\HealthRecord;
use App\Models\InsuranceProfile;
use App\Models\Activity;
use Illuminate\Database\Seeder;

class PetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'testUser@email.com')->first();

        if (!$user) return;

        // Pet 1: Data-rich (Bella)
        $bella = Pet::create([
            'user_id' => $user->id,
            'name' => 'Bella',
            'type' => 'dog',
            'gender' => 'female',
            'breed' => 'Golden Retriever',
            'birthday' => '2021-05-20',
            'weight' => 28.5,
        ]);

        // Health Records for Bella
        $bella->healthRecords()->createMany([
            ['type' => 'weight', 'value' => '28.5kg', 'recorded_at' => now()->subDays(60)],
            ['type' => 'weight', 'value' => '29.0kg', 'recorded_at' => now()->subDays(30)],
            ['type' => 'vaccine', 'value' => json_encode(['name' => 'Rabies']), 'recorded_at' => now()->subDays(10)],
            ['type' => 'checkup', 'value' => json_encode(['note' => 'Healthy heart']), 'recorded_at' => now()->subDays(5)],
        ]);

        // Activities for Bella
        $bella->activities()->createMany([
            ['type' => 'walk', 'description' => 'Morning walk in park', 'duration_minutes' => 45, 'occurred_at' => now()->subHours(2)],
            ['type' => 'play', 'description' => 'Fetch in backyard', 'duration_minutes' => 20, 'occurred_at' => now()->subHours(5)],
        ]);

        // Calculate initial insurance for Bella
        app(\App\Services\RiskScoreService::class)->calculate($bella);

        // Pet 2: Data-poor (Milo)
        $milo = Pet::create([
            'user_id' => $user->id,
            'name' => 'Milo',
            'type' => 'cat',
            'gender' => 'male',
            'breed' => 'British Shorthair',
            'birthday' => '2018-10-12',
            'weight' => 5.2,
        ]);

        // Only one old record
        $milo->healthRecords()->create([
            'type' => 'weight',
            'value' => '5.2kg',
            'recorded_at' => now()->subDays(120),
        ]);

        // Calculate initial insurance for Milo
        app(\App\Services\RiskScoreService::class)->calculate($milo);
    }
}
