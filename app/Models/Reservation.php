<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'scooter_id',
        'reserved_at',
        'expires_at',
        'cancelled_at',
        'started_at',
    ];

    protected function casts(): array
    {
        return [
            'reserved_at' => 'datetime',
            'expires_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'started_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Reservation $reservation): void {
            if (empty($reservation->uuid)) {
                $reservation->uuid = (string) Str::uuid();
            }
        });
    }

    public function scooter(): BelongsTo
    {
        return $this->belongsTo(Scooter::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function ride(): HasOne
    {
        return $this->hasOne(Ride::class);
    }
}