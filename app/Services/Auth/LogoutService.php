<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;

class LogoutService
{
    public function execute(User $user): void
    {
        $user->currentAccessToken()?->delete();
    }
}
