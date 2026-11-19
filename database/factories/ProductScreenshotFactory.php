<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductScreenshot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductScreenshot>
 */
class ProductScreenshotFactory extends Factory
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
            'image' => 'screenshots/'.fake()->uuid().'.png',
            'caption' => ['en' => fake()->sentence()],
            'sort_order' => 0,
        ];
    }
}
