<?php

namespace Database\Factories;

use App\Enums\SmsVendorDriver;
use App\Models\SmsVendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SmsVendor>
 */
class SmsVendorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'BulkSMSBD',
            'driver' => SmsVendorDriver::BulkSmsBd,
            'api_key' => fake()->sha1(),
            'sender_id' => '880961'.fake()->numerify('#######'),
            'priority' => 1,
            'is_active' => true,
        ];
    }

    /**
     * A vendor the schools keep but don't send through.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
