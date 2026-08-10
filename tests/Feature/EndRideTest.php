<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Scooter;
use App\Models\User;
use App\Models\ScooterStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EndRideTest extends TestCase
{
    use RefreshDatabase;

    //protected bool $seed = true;

    public function test_user_can_end_active_ride(): void
    {
       
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $availableStatus = ScooterStatus::where('slug', 'available')->firstOrFail();

        $scooter = Scooter::factory()->create([
            'scooter_status_id' => $availableStatus->id,
        ]);
        $this->postJson(
            "/api/v1/scooters/{$scooter->uuid}/reserve"
        )->assertCreated();
        $unlockResponse = $this->postJson(
            "/api/v1/scooters/{$scooter->uuid}/unlock",
            [
                'latitude' => 52.520008,
                'longitude' => 13.404954,
            ]
        );

        $unlockResponse->assertCreated();

        $rideUuid = $unlockResponse->json('data.uuid');

        $response = $this->postJson(
            "/api/v1/rides/{$rideUuid}/end",
            [
                'latitude' => 52.520008,
                'longitude' => 13.404954,
            ]
        );

        $response->assertOk();

        $response->assertJsonStructure([
            'data' => [
                'uuid',
                'started_at',
                'ended_at',
                'distance_meters',
                'duration_seconds',
                'cost_cents',
                'user',
                'scooter',
                'reservation',
                'created_at',
            ],
        ]);

        $ride = \App\Models\Ride::where('uuid', $rideUuid)->first();

        $this->assertNotNull($ride);
        $this->assertNotNull($ride->ended_at);
        $this->assertNotNull($ride->duration_seconds);
        $this->assertNotNull($ride->cost_cents);

        $this->assertDatabaseHas('rides', [
            'id' => $ride->id,
        ]);
    }
}