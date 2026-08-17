<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Scooter;
use App\Models\User;
use App\Models\ScooterStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RideTest extends TestCase
{
    use RefreshDatabase;

    //protected bool $seed = true;

    public function test_user_can_unlock_reserved_scooter(): void
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

        $response = $this->postJson(
            "/api/v1/scooters/{$scooter->uuid}/unlock",
            [
                'latitude' => 52.520008,
                'longitude' => 13.404954,
            ]
        );

 
        $response->assertCreated();

        $response->assertJsonStructure([
            'data' => [
                'uuid',
                'started_at',
                'scooter',
            ],
        ]);
        $this->assertDatabaseHas('rides', [
            'user_id' => $user->id,
            'scooter_id' => $scooter->id,
        ]);
        
        $reservation = \App\Models\Reservation::where('user_id', $user->id)
            ->where('scooter_id', $scooter->id)
            ->first();

        $this->assertNotNull($reservation);
        $this->assertNotNull($reservation->started_at);
        $scooter->refresh();

    $this->assertSame(
        'in_use',
        $scooter->status->slug
    );
    }
}