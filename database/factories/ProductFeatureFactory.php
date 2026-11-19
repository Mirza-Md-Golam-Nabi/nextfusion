<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductFeature;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductFeature>
 */
class ProductFeatureFactory extends Factory
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
            'icon' => '✨',
            'title' => ['en' => fake()->words(3, true)],
            'description' => ['en' => fake()->sentence()],
            'sort_order' => 0,
        ];
    }
}
