<?php

declare(strict_types=1);

namespace App\Services\Scooter;

use App\Models\Scooter;
use App\Models\ScooterStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ScooterService
{
    public function getAll(): LengthAwarePaginator
    {
        return Scooter::with('status')
            ->latest()
            ->paginate(15);
    }

    public function create(array $data): Scooter
    {
        if (isset($data['status'])) {
            $data['scooter_status_id'] = ScooterStatus::where(
                'slug',
                $data['status']
            )->value('id');

            unset($data['status']);
        }

        return Scooter::create($data)->load('status');
    }

    public function update(Scooter $scooter, array $data): Scooter
    {
        if (isset($data['status'])) {
            $data['scooter_status_id'] = ScooterStatus::where(
                'slug',
                $data['status']
            )->value('id');

            unset($data['status']);
        }
        $scooter->update($data);

        return $scooter->fresh()->load('status');
    }

    public function delete(Scooter $scooter): void
    {
        $scooter->delete();
    }
    public function findNearby(
        float $latitude,
        float $longitude,
        int $radius
    ): Collection {

        $earthRadius = 6371000;

        $distanceSql = '(
            ? * acos(
                cos(radians(?))
                * cos(radians(latitude))
                * cos(radians(longitude) - radians(?))
                + sin(radians(?))
                * sin(radians(latitude))
            )
        )';

        return Scooter::query()
            ->with('status')
            ->whereHas('status', function ($query) {
                $query->where('slug', 'available');
            })
            ->select('scooters.*')
            ->selectRaw(
                "{$distanceSql} AS distance",
                [
                    $earthRadius,
                    $latitude,
                    $longitude,
                    $latitude,
                ]
            )
            ->whereRaw(
                "{$distanceSql} <= ?",
                [
                    $earthRadius,
                    $latitude,
                    $longitude,
                    $latitude,
                    $radius,
                ]
            )
            ->orderBy('distance')
            ->get();
    }
    public function updateLocation(
    Scooter $scooter,
    array $data
    ): Scooter
    {
        $scooter->update([
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'battery_percentage' => $data['battery_percentage'],
            'last_seen_at' => now(),
        ]);

        return $scooter->fresh()->load('status');
    }
}
