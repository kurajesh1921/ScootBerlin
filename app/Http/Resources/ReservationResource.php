<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,

            'reserved_at' => $this->reserved_at,

            'expires_at' => $this->expires_at,

            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ],

            'scooter' => [
                'uuid' => $this->scooter->uuid,
                'vehicle_number' => $this->scooter->vehicle_number,
                'status' => $this->scooter->status->name,
            ],
        ];
    }
}