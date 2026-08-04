<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Ride extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'scooter_id',
        'reservation_id',
        'started_at',
        'ended_at',
        'start_latitude',
        'start_longitude',
        'end_latitude',
        'end_longitude',
        'distance_meters',
        'duration_seconds',
        'cost_cents',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',

            'start_latitude' => 'float',
            'start_longitude' => 'float',

            'end_latitude' => 'float',
            'end_longitude' => 'float',

            'distance_meters' => 'integer',
            'duration_seconds' => 'integer',
            'cost_cents' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Ride $ride): void {

            if (empty($ride->uuid)) {
                $ride->uuid = (string) Str::uuid();
            }

        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scooter(): BelongsTo
    {
        return $this->belongsTo(Scooter::class);
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }
}