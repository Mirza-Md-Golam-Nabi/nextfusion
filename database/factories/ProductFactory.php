<?php

namespace Database\Factories;

use App\Enums\ProductStatus;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => ['en' => Str::title($name)],
            'slug' => Str::slug($name),
            'tagline' => ['en' => fake()->sentence()],
            'short_description' => ['en' => fake()->paragraph()],
            'description' => ['en' => '<p>'.fake()->paragraphs(2, true).'</p>'],
            'icon' => '🚀',
            'status' => ProductStatus::Live,
            'demo_url' => fake()->url(),
            'demo_credentials' => [
                ['role' => 'Admin', 'username' => 'admin@demo.test', 'password' => 'password'],
            ],
            'demo_reset_minutes' => 30,
            'is_featured' => false,
            'is_active' => true,
            'sort_order' => 0,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => ['is_active' => false]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes): array => ['is_featured' => true]);
    }

    public function upcoming(): static
    {
        return $this->state(fn (array $attributes): array => ['status' => ProductStatus::Upcoming]);
    }
}
