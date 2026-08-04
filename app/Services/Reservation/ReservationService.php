<?php

declare(strict_types=1);

namespace App\Services\Reservation;

use App\Models\Reservation;
use App\Models\Scooter;
use App\Models\ScooterStatus;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ReservationService
{
    public function reserve(
        User $user,
        Scooter $scooter
    ): Reservation {

        return DB::transaction(function () use ($user, $scooter) {

            $scooter = Scooter::query()
                ->lockForUpdate()
                ->findOrFail($scooter->id);

            if ($scooter->status->slug !== 'available') {
               throw new ScooterNotAvailableException();
            }

            $reservation = Reservation::create([
                'user_id' => $user->id,
                'scooter_id' => $scooter->id,
                'reserved_at' => now(),
                'expires_at' => now()->addMinutes(
                    config('scooter.reservation_timeout_minutes')
                ),
            ]);

            $reservedStatusId = ScooterStatus::where(
                'slug',
                'reserved'
            )->value('id');

            $scooter->update([
                'scooter_status_id' => $reservedStatusId,
            ]);

            return $reservation->load([
                'user',
                'scooter.status',
            ]);
        });
    }
}