<?php

namespace Database\Factories;

use App\Enums\BillingCycle;
use App\Models\PricingPlan;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PricingPlan>
 */
class PricingPlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'name' => ['en' => fake()->randomElement(['Basic', 'Standard', 'Premium'])],
            'price' => fake()->randomFloat(2, 500, 20000),
            'billing_cycle' => BillingCycle::Monthly,
            'features' => ['en' => fake()->sentences(3)],
            'is_popular' => false,
            'sort_order' => 0,
        ];
    }
}
