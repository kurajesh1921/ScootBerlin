<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Scooter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ScooterLocationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_scooter_location_and_battery(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $availableStatus = \App\Models\ScooterStatus::where(
            'slug',
            'available'
        )->firstOrFail();

        $scooter = Scooter::factory()->create([
            'scooter_status_id' => $availableStatus->id,
            'vehicle_number' => 'TEST-' . Str::uuid(),
            'latitude' => 52.500000,
            'longitude' => 13.400000,
            'battery_percentage' => 80,
            'last_seen_at' => now()->subHour(),
        ]);

        $response = $this->postJson(
            "/api/v1/scooters/{$scooter->uuid}/location",
            [
                'latitude' => 52.520008,
                'longitude' => 13.404954,
                'battery_percentage' => 65,
            ]
        );

        $response->assertOk();

        $response->assertJsonStructure([
            'data' => [
                'uuid',
                'battery_percentage',
                'location' => [
                    'latitude',
                    'longitude',
                ],
                'last_seen_at',
            ],
        ]);
        $response->assertJsonPath(
            'data.location.latitude',
            52.520008
        );

        $response->assertJsonPath(
            'data.location.longitude',
            13.404954
        );

        $response->assertJsonPath(
            'data.battery_percentage',
            65
        );
        $this->assertDatabaseHas('scooters', [
            'id' => $scooter->id,
            'latitude' => 52.520008,
            'longitude' => 13.404954,
            'battery_percentage' => 65,
        ]);

        $scooter->refresh();

        $this->assertNotNull($scooter->last_seen_at);
    }
    public function test_scooter_location_rejects_invalid_telemetry(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $availableStatus = \App\Models\ScooterStatus::where(
            'slug',
            'available'
        )->firstOrFail();

        $scooter = Scooter::factory()->create([
            'scooter_status_id' => $availableStatus->id,
            'vehicle_number' => 'TEST-' . Str::uuid(),
        ]);

        $response = $this->postJson(
            "/api/v1/scooters/{$scooter->uuid}/location",
            [
                'latitude' => 91,
                'longitude' => 181,
                'battery_percentage' => 101,
            ]
        );

        $response->assertUnprocessable();

        $response->assertJsonValidationErrors([
            'latitude',
            'longitude',
            'battery_percentage',
        ]);
    }
}