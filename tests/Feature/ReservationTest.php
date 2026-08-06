<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Scooter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;
    
    protected bool $seed = true;

    public function test_user_can_reserve_available_scooter(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $scooter = Scooter::factory()->create([
            'scooter_status_id' => 1,
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