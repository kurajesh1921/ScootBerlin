<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Seed the application's roles.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Administrator',
                'slug' => 'admin',
                'description' => 'Full access to the ScootBerlin platform.',
            ],
            [
                'name' => 'Operations',
                'slug' => 'operations',
                'description' => 'Manages scooters, maintenance, and fleet operations.',
            ],
            [
                'name' => 'Rider',
                'slug' => 'rider',
                'description' => 'Customer who rents and rides scooters.',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }
    }
}
