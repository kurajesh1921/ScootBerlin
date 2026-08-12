<?php

declare(strict_types=1);

namespace App\Services\Ride;

use App\Models\Reservation;
use App\Models\Ride;
use App\Models\Scooter;
use App\Models\ScooterStatus;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Illuminate\Auth\Access\AuthorizationException;
use App\Exceptions\ReservationExpiredException;
use App\Exceptions\RideAlreadyEndedException;
use App\Exceptions\ScooterNotReservedException;
use App\Exceptions\ReservationNotFoundException;

class RideService
{
    public function startRide(
        User $user,
        Scooter $scooter
    ): Ride {

        return DB::transaction(function () use ($user, $scooter) {

            /*
             * Lock scooter row
             */
            $scooter = Scooter::query()
                ->lockForUpdate()
                ->findOrFail($scooter->id);

            /*
             * Scooter must be reserved
             */
            if ($scooter->status->slug !== 'reserved') {
                throw new ScooterNotReservedException(
                    'Scooter is not reserved.'
                );
            }

            /*
             * Find active reservation
             */
            /*
            * Find reservation for scooter
            */
            $reservation = Reservation::query()
                ->where('scooter_id', $scooter->id)
                ->latest()
                ->first();

            if (! $reservation) {
                 throw new ReservationNotFoundException(
                    'Reservation not found.'
                );
            }

            /*
            * Verify reservation owner
            */
            if ($reservation->user_id !== $user->id) {
                throw new AuthorizationException(
                    'This reservation belongs to another user.'
                );
            }

            /*
            * Verify reservation has not been cancelled
            */
            if ($reservation->cancelled_at !== null) {
                throw new RuntimeException(
                    'Reservation has been cancelled.'
                );
            }

            /*
            * Verify reservation has not expired
            */
            if ($reservation->expires_at->isPast()) {
                throw new ReservationExpiredException(
                    'Reservation has expired.'
                );
            }

            /*
            * Verify ride has not already started
            */
            if ($reservation->started_at !== null) {
                throw new RuntimeException(
                    'Ride has already started.'
                );
            }

            /*
             * Create ride
             */
            $ride = Ride::create([
                'user_id' => $user->id,
                'scooter_id' => $scooter->id,
                'reservation_id' => $reservation->id,

                'started_at' => now(),

                'start_latitude' => $scooter->latitude,
                'start_longitude' => $scooter->longitude,
            ]);

            /*
             * Mark reservation as started
             */
            $reservation->update([
                'started_at' => now(),
            ]);

            /*
             * Change scooter status
             */
            $inUseStatusId = ScooterStatus::query()
                ->where('slug', 'in_use')
                ->value('id');

            $scooter->update([
                'scooter_status_id' => $inUseStatusId,
            ]);

            return $ride->load([
                'user',
                'scooter.status',
                'reservation',
            ]);
        });
    }
    public function endRide(
        User $user,
        Ride $ride
    ): Ride
    {
        return DB::transaction(function () use ($user, $ride) {

            $scooter = Scooter::query()
            ->lockForUpdate()
            ->findOrFail($ride->scooter_id);

            if ($ride->user_id !== $user->id) {
                throw new AuthorizationException(
                    'This ride belongs to another user.'
                );
            }
            /*
            * Verify ride has not already ended
            */
            if ($ride->ended_at !== null) {
                throw new RideAlreadyEndedException(
                    'Ride has already ended.'
                );
            }
            /*
            * Scooter must be in use
            */
            if ($scooter->status->slug !== 'in_use') {
                throw new RuntimeException(
                    'Scooter is not currently in use.'
                );
            }

            $endedAt = now();

            $durationSeconds = (int) $ride->started_at
                ->diffInSeconds($endedAt);
            $unlockFee = 100; // €1.00

            $pricePerMinute = 25; // €0.25

            $minutes = (int) ceil($durationSeconds / 60);

            $costCents = $unlockFee + ($minutes * $pricePerMinute);
            $ride->update([
                'ended_at' => $endedAt,
                'duration_seconds' => $durationSeconds,
                'cost_cents' => $costCents,

                /*
                * Current scooter position
                */
                'end_latitude' => $scooter->latitude,
                'end_longitude' => $scooter->longitude,
            ]);
            $availableStatusId = ScooterStatus::query()
                ->where('slug', 'available')
                ->value('id');

            $scooter->update([
                'scooter_status_id' => $availableStatusId,
            ]);
            return $ride->fresh()->load([
                'user',
                'scooter.status',
                'reservation',
            ]);

        });
    }
}