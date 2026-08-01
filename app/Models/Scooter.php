<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Scooter extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'scooter_status_id',
        'vehicle_number',
        'serial_number',
        'qr_code',
        'battery_percentage',
        'latitude',
        'longitude',
        'last_seen_at',
    ];

    protected function casts(): array
    {
        return [
            'battery_percentage' => 'integer',
            'latitude' => 'float',
            'longitude' => 'float',
            'last_seen_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Scooter $scooter): void {
            if (empty($scooter->uuid)) {
                $scooter->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(ScooterStatus::class, 'scooter_status_id');
    }
}
