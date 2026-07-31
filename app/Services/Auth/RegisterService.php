<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RegisterService
{
    /**
     * Register a new rider.
     *
     * @param array<string, mixed> $data
     */
    public function execute(array $data): User
    {
        $riderRole = Role::where('slug', 'rider')->first();

        if (! $riderRole) {
            throw new ModelNotFoundException('Rider role not found.');
        }

        return User::create([
            'role_id'    => $riderRole->id,
            'name'       => $data['name'],
            'email'      => $data['email'],
            'phone'      => $data['phone'] ?? null,
            'password'   => $data['password'],
            'is_active'  => true,
        ]);
    }
}