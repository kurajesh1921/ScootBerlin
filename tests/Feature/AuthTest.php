<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
                'password' => bcrypt('password'),
        ]);
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertOk();

        $response->assertJson([
            'success' => true,
            'message' => 'Login successful.',
        ]);

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'access_token',
                'token_type',
                'user',
            ],
        ]);
    }
}