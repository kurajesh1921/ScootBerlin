<?php

declare(strict_types=1);

namespace App\Services\Scooter;

use App\Models\Scooter;
use App\Models\ScooterStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
}
