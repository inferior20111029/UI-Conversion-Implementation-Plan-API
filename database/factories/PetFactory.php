<?php

namespace Database\Factories;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PetFactory extends Factory
{
    protected $model = Pet::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->firstName(),
            'type' => fake()->randomElement(['dog', 'cat']),
            'breed' => fake()->word(),
            'birthday' => fake()->dateTimeBetween('-12 years', '-1 year')->format('Y-m-d'),
        ];
    }
}
