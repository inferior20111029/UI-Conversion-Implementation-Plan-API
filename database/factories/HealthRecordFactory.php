<?php

namespace Database\Factories;

use App\Models\HealthRecord;
use App\Models\Pet;
use Illuminate\Database\Eloquent\Factories\Factory;

class HealthRecordFactory extends Factory
{
    protected $model = HealthRecord::class;

    public function definition(): array
    {
        return [
            'pet_id' => Pet::factory(),
            'type' => fake()->randomElement(['weight', 'vaccine', 'checkup']),
            'value' => json_encode(['note' => fake()->sentence()]),
            'recorded_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
