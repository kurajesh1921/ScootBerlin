<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Scooter;
use App\Models\User;
use App\Models\ScooterStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Support\Str;

class ReservationTest extends TestCase
{
    use RefreshDatabase;
    
    //protected bool $seed = true;

    public function test_user_can_reserve_available_scooter(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $availableStatus = ScooterStatus::where('slug', 'available')->firstOrFail();

        $scooter = Scooter::factory()->create([
            'scooter_status_id' => $availableStatus->id,
            'vehicle_number' => 'TEST-' . \Illuminate\Support\Str::uuid(),
        ]);

        $response = $this->postJson( "/api/v1/scooters/{$scooter->uuid}/reserve"
        );

       $response->assertCreated();

        $response->assertJsonStructure([
            'data' => [
                'uuid',
                'reserved_at',
                'expires_at',
                'user',
                'scooter',
            ],
        ]);

        $this->assertDatabaseHas('reservations', [
            'user_id' => $user->id,
            'scooter_id' => $scooter->id,
        ]);
    }
}