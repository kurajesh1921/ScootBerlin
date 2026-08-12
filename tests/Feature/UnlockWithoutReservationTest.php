<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Scooter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UnlockWithoutReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_unlock_scooter_without_reservation(): void
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
            "/api/v1/scooters/{$scooter->uuid}/unlock",
            [
                'latitude' => 52.520008,
                'longitude' => 13.404954,
            ]
        );

        $response->assertStatus(409);

        $response->assertJson([
            'message' => 'Scooter is not reserved.',
        ]);

        $this->assertDatabaseMissing('rides', [
            'user_id' => $user->id,
            'scooter_id' => $scooter->id,
        ]);
    }
}