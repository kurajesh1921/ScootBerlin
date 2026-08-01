<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Scooter;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Scooter
 */
class ScooterResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'vehicle_number' => $this->vehicle_number,
            'serial_number' => $this->serial_number,
            'qr_code' => $this->qr_code,

            'status' => [
                'id' => $this->status->id,
                'name' => $this->status->name,
                'slug' => $this->status->slug,
            ],

            'battery_percentage' => $this->battery_percentage,

            'location' => [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
            ],

            'last_seen_at' => $this->last_seen_at,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
