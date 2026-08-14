<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Ride;
use App\Models\Scooter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EndRideValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_end_ride_requires_latitude_and_longitude(): void
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

        $this->postJson(
            "/api/v1/scooters/{$scooter->uuid}/reserve"
        )->assertCreated();

        $this->postJson(
            "/api/v1/scooters/{$scooter->uuid}/unlock",
            [
                'latitude' => 52.520008,
                'longitude' => 13.404954,
            ]
        )->assertCreated();

        $ride = Ride::where('user_id', $user->id)
            ->where('scooter_id', $scooter->id)
            ->whereNull('ended_at')
            ->firstOrFail();

        $response = $this->postJson(
            "/api/v1/rides/{$ride->uuid}/end"
        );

        $response->assertUnprocessable();

        $response->assertJsonValidationErrors([
            'latitude',
            'longitude',
        ]);
    }
    public function test_end_ride_rejects_invalid_coordinate_ranges(): void
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

        $this->postJson(
            "/api/v1/scooters/{$scooter->uuid}/reserve"
        )->assertCreated();

        $this->postJson(
            "/api/v1/scooters/{$scooter->uuid}/unlock",
            [
                'latitude' => 52.520008,
                'longitude' => 13.404954,
            ]
        )->assertCreated();

        $ride = Ride::where('user_id', $user->id)
            ->where('scooter_id', $scooter->id)
            ->whereNull('ended_at')
            ->firstOrFail();

        $response = $this->postJson(
            "/api/v1/rides/{$ride->uuid}/end",
            [
                'latitude' => 91,
                'longitude' => 181,
            ]
        );

        $response->assertUnprocessable();

        $response->assertJsonValidationErrors([
            'latitude',
            'longitude',
        ]);
    }
}