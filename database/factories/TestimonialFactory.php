<?php

namespace Database\Factories;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Testimonial>
 */
class TestimonialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => null,
            'client_name' => ['en' => fake()->name()],
            'client_designation' => ['en' => fake()->jobTitle()],
            'organization' => ['en' => fake()->company()],
            'content' => ['en' => fake()->paragraph()],
            'rating' => fake()->numberBetween(4, 5),
            'is_active' => true,
            'sort_order' => 0,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => ['is_active' => false]);
    }
}
