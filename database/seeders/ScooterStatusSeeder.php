<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ScooterStatus;
use Illuminate\Database\Seeder;

class ScooterStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'Available',
                'slug' => 'available',
                'description' => 'Ready to rent.',
            ],
            [
                'name' => 'Reserved',
                'slug' => 'reserved',
                'description' => 'Reserved by a rider.',
            ],
            [
                'name' => 'In Use',
                'slug' => 'in_use',
                'description' => 'Currently being ridden.',
            ],
            [
                'name' => 'Charging',
                'slug' => 'charging',
                'description' => 'Charging battery.',
            ],
            [
                'name' => 'Maintenance',
                'slug' => 'maintenance',
                'description' => 'Under maintenance.',
            ],
            [
                'name' => 'Offline',
                'slug' => 'offline',
                'description' => 'No heartbeat from scooter.',
            ],
        ];

        foreach ($statuses as $status) {
            ScooterStatus::updateOrCreate(
                ['slug' => $status['slug']],
                $status
            );
        }
    }
}
