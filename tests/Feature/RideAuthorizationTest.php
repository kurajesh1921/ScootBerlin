<?php
declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Scooter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RideAuthorizationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_user_cannot_unlock_another_users_reserved_scooter(): void
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
        ]);
        $this->postJson(
            "/api/v1/scooters/{$scooter->uuid}/reserve"
        )->assertCreated();

        Sanctum::actingAs($userB);
        $response = $this->postJson(
            "/api/v1/scooters/{$scooter->uuid}/unlock",
            [
                'latitude' => 52.520008,
                'longitude' => 13.404954,
            ]
        );
        
        $response->assertForbidden();

        $response->assertJson([
            'message' => 'This reservation belongs to another user.',
        ]);

        $this->assertDatabaseMissing('rides', [
            'user_id' => $userB->id,
            'scooter_id' => $scooter->id,
        ]);
    }
}
