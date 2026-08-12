<?php
declare(strict_types=1);
namespace Tests\Feature;
use App\Models\Scooter;
use App\Models\Ride;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Str;

class RideAlreadyEndedTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_user_cannot_end_already_ended_ride(): void
    {
        $user=  User::factory()->create();
        Sanctum::actingAs($user);

        $availableStatus= \App\Models\ScooterStatus::where(
            'slug',
            'available'
        )->FirstOrFail();

        $scooter = Scooter::factory()->create(
            [
                'scooter_status_id' =>$availableStatus->id,
                'vehicle_number' => 'TEST-' . Str::uuid(),
            ]
        );
        $this->postJson("/api/v1/scooters/{$scooter->uuid}/reserve"
        )->assertCreated();
        $this->postJson(
            "/api/v1/scooters/{$scooter->uuid}/unlock",
            [
                'latitude' => 52.520008,
                'longitude' => 13.404954,
            ]
        )->assertCreated();
        $ride= Ride::where('user_id',$user->id)
        ->where('scooter_id',$scooter->id)
        ->whereNull('ended_at')
        ->FirstOrFail();

        $this->postJson(
            "/api/v1/rides/{$ride->uuid}/end",
            [
                'latitude' => 52.520008,
                'longitude' => 13.404954,
            ]
        )->assertOk();
        // Try to end the same ride again.
        $response = $this->postJson(
            "/api/v1/rides/{$ride->uuid}/end",
            [
                'latitude' => 52.520008,
                'longitude' => 13.404954,
            ]
        );

        $response->assertStatus(409);

        $response->assertJson([
            'message' => 'Ride has already ended.',
        ]);
        $ride->refresh();

        $this->assertNotNull($ride->ended_at);

    }
}
