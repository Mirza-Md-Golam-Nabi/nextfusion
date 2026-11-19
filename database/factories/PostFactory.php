<?php

namespace Database\Factories;

use App\Enums\PostCategory;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(5);

        return [
            'product_id' => null,
            'title' => ['en' => $title],
            'slug' => Str::slug($title),
            'category' => fake()->randomElement(PostCategory::cases()),
            'excerpt' => ['en' => fake()->sentence(15)],
            'content' => ['en' => '<p>'.implode('</p><p>', fake()->paragraphs(3)).'</p>'],
            'published_at' => fake()->dateTimeBetween('-3 months', '-1 day'),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes): array => ['published_at' => null]);
    }

    public function scheduled(): static
    {
        return $this->state(fn (array $attributes): array => ['published_at' => now()->addWeek()]);
    }
}
