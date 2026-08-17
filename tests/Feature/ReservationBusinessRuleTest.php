<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Scooter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ReservationBusinessRuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_reserve_already_reserved_scooter(): void
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
            'vehicle_number' => 'TEST-' . \Illuminate\Support\Str::uuid(),
        ]);

        $this->postJson(
            "/api/v1/scooters/{$scooter->uuid}/reserve"
        )->assertCreated();
        $this->assertDatabaseHas('reservations', [
            'user_id' => $userA->id,
            'scooter_id' => $scooter->id,
        ]);

        $scooter->refresh();

        $this->assertSame(
            'reserved',
            $scooter->status->slug
        );
        Sanctum::actingAs($userB);
        $response = $this->postJson(
            "/api/v1/scooters/{$scooter->uuid}/reserve"
        );

        $response->assertStatus(409);

        $response->assertJson([
            'message' => 'Scooter is not available.',
        ]);
        $this->assertDatabaseMissing('reservations', [
            'user_id' => $userB->id,
            'scooter_id' => $scooter->id,
        ]);
    }
}