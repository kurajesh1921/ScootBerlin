<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RideResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'uuid' => $this->uuid,

            'started_at' => $this->started_at,

            'ended_at' => $this->ended_at,

            'distance_meters' => $this->distance_meters,

            'duration_seconds' => $this->duration_seconds,

            'cost_cents' => $this->cost_cents,

            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],

            'scooter' => [
                'uuid' => $this->scooter->uuid,
                'vehicle_number' => $this->scooter->vehicle_number,

                'status' => [
                    'name' => $this->scooter->status->name,
                    'slug' => $this->scooter->status->slug,
                ],
            ],

            'reservation' => [
                'uuid' => $this->reservation->uuid,
                'expires_at' => $this->reservation->expires_at,
            ],

            'created_at' => $this->created_at,
        ];
    }
}