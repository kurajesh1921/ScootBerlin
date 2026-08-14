<?php

declare(strict_types=1);

namespace Tests\Feature;
use App\Models\User;
use App\Models\Scooter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class NearbyScooterTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_available_scooters_within_radius(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);
        $availableStatus = \App\Models\ScooterStatus::where(
            'slug',
            'available'
        )->firstOrFail();

        $reservedStatus = \App\Models\ScooterStatus::where(
            'slug',
            'reserved'
        )->firstOrFail();

        // User's location: Berlin
        $latitude = 0.000000;
        $longitude = 0.000000;

        // Very close to the user.
        $nearbyScooter = Scooter::factory()->create([
            'scooter_status_id' => $availableStatus->id,
            'vehicle_number' => 'TEST-' . Str::uuid(),
            'latitude' => 0.000100,
            'longitude' => 0.000100,
        ]);

        // Within 1 km, but farther away than the first scooter.
        $fartherScooter = Scooter::factory()->create([
            'scooter_status_id' => $availableStatus->id,
            'vehicle_number' => 'TEST-' . Str::uuid(),
            'latitude' => 0.005000,
            'longitude' => 0.005000,
        ]);

        // Nearby, but reserved — should NOT be returned.
        Scooter::factory()->create([
            'scooter_status_id' => $reservedStatus->id,
            'vehicle_number' => 'TEST-' . Str::uuid(),
            'latitude' => 0.000500,
            'longitude' => 0.000500,
        ]);

        // Outside the 1 km radius.
        Scooter::factory()->create([
            'scooter_status_id' => $availableStatus->id,
            'vehicle_number' => 'TEST-' . Str::uuid(),
            'latitude' => 0.050000,
            'longitude' => 0.050000,
        ]);

        $response = $this->getJson(
            '/api/v1/scooters/nearby?' . http_build_query([
                'latitude' => $latitude,
                'longitude' => $longitude,
                'radius' => 1000,
            ])
        );

        $response->assertOk();
        //$response->dump();
        $response->assertJsonCount(2, 'data');

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'uuid',
                    'vehicle_number',
                    'status',
                    'battery_percentage',
                    'location' => [
                        'latitude',
                        'longitude',
                    ],
                    'distance',
                ],
            ],
        ]);

        $response->assertJsonPath(
            'data.0.uuid',
            $nearbyScooter->uuid
        );

        $response->assertJsonPath(
            'data.1.uuid',
            $fartherScooter->uuid
        );

        $response->assertJsonPath(
            'data.0.distance',
            fn ($distance) => is_numeric($distance)
        );

        $response->assertJsonPath(
            'data.1.distance',
            fn ($distance) => is_numeric($distance)
        );
    }
    public function test_nearby_rejects_radius_below_minimum(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->getJson(
            '/api/v1/scooters/nearby?' . http_build_query([
                'latitude' => 0,
                'longitude' => 0,
                'radius' => 50,
            ])
        );

        $response->assertUnprocessable();

        $response->assertJsonValidationErrors([
            'radius',
        ]);
    }
}