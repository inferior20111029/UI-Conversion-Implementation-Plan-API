<?php

namespace Database\Factories;

use App\Models\Affiliate;
use Illuminate\Database\Eloquent\Factories\Factory;

class AffiliateFactory extends Factory
{
    protected $model = Affiliate::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company() . ' Insurance',
            'description' => fake()->sentence(),
            'affiliate_url' => fake()->url(),
            'commission_rate' => fake()->randomFloat(2, 5, 15),
            'is_active' => true,
            'target_pet_types' => ['dog', 'cat'],
            'min_risk_score' => 0,
            'max_risk_score' => 200,
        ];
    }
}
