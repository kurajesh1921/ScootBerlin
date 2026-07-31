<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

class LoginService
{
    /**
     * @param array<string,mixed> $credentials
     * @return array<string,mixed>
     */
    public function execute(array $credentials): array
    {
        /** @var User|null $user */
        $user = User::with('role')
            ->where('email', $credentials['email'])
            ->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw new AuthenticationException('Invalid email or password.');
        }

        if (! $user->is_active) {
            throw new AuthenticationException('Your account has been disabled.');
        }

        // Optional: revoke previous tokens for single-device login
        // $user->tokens()->delete();

        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ];
    }
}