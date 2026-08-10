<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Scooter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Scooter>
 */
class ScooterFactory extends Factory
{
    protected $model = Scooter::class;

    public function definition(): array
    {
        return [
            'scooter_status_id' => fake()->randomElement([
                1, 1, 1, 1, 1, 1, 1,
                2,
                3,
                4,
                5,
            ]),

            'vehicle_number' => sprintf(
                'BER-%04d',
                fake()->unique()->numberBetween(1, 9999)
            ),

            'serial_number' => strtoupper(
                fake()->unique()->bothify('SC########')
            ),

            'qr_code' => fake()->unique()->uuid(),

            'battery_percentage' => fake()->numberBetween(15, 100),

            'latitude' => fake()->randomFloat(7, 52.4700000, 52.5700000),

            'longitude' => fake()->randomFloat(7, 13.3000000, 13.5000000),

            'last_seen_at' => fake()->dateTimeBetween('-30 minutes', 'now'),
        ];
    }
}