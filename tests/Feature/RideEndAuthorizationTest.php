<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Ride;
use App\Models\Scooter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RideEndAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_end_another_users_active_ride(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        Sanctum::actingAs($userA);

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

        $ride = Ride::where('user_id', $userA->id)
            ->where('scooter_id', $scooter->id)
            ->whereNull('ended_at')
            ->firstOrFail();

        Sanctum::actingAs($userB);

        $response = $this->postJson(
            "/api/v1/rides/{$ride->uuid}/end",
            [
                'latitude' => 52.520008,
                'longitude' => 13.404954,
            ]
        );

        $response->assertForbidden();

        $response->assertJson([
            'message' => 'This ride belongs to another user.',
        ]);
        $this->assertDatabaseHas('rides', [
            'id' => $ride->id,
            'user_id' => $userA->id,
            'ended_at' => null,
        ]);
    }
}