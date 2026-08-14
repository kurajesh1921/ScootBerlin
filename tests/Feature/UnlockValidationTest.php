<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Scooter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UnlockValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_unlock_requires_latitude_and_longitude(): void
    {
        $user = User::Factory()->create();
        Sanctum::actingAs($user);

        $reservedStatus = \App\Models\ScooterStatus::where(
            'slug',
            'reserved'
        )->firstOrFail();
        $scooter = Scooter::factory()->create(
            ['scooter_status_id' => $reservedStatus->id,
            'vehicle_number' => 'TEST-' . STR::uuid(),
            ]
        );
        $response = $this->postJson("/api/v1/scooters/{$scooter->uuid}/unlock");
        
        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(
            [
                'latitude',
                'longitude'
            ]
        );

    }
    public function test_unlock_rejects_invalid_coordinate_ranges(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $reservedStatus = \App\Models\ScooterStatus::where(
            'slug',
            'reserved'
        )->firstOrFail();

        $scooter = Scooter::factory()->create([
            'scooter_status_id' => $reservedStatus->id,
            'vehicle_number' => 'TEST-' . Str::uuid(),
        ]);

        $response = $this->postJson(
            "/api/v1/scooters/{$scooter->uuid}/unlock",
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
