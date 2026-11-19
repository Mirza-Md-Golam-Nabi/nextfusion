<?php

namespace Database\Factories;

use App\Enums\SmsVendorDriver;
use App\Models\SmsResponseCode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SmsResponseCode>
 */
class SmsResponseCodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'driver' => SmsVendorDriver::BulkSmsBd,
            'code' => (string) fake()->unique()->numberBetween(1000, 9999),
            'message' => fake()->sentence(3),
            'is_success' => false,
        ];
    }

    /**
     * A code that means the provider accepted the SMS.
     */
    public function success(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_success' => true,
        ]);
    }
}
