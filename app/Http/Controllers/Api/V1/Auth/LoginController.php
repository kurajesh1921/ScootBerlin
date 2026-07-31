<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\Auth\LoginService;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function __construct(
        private readonly LoginService $loginService
    ) {
    }

    public function __invoke(LoginRequest $request): JsonResponse
    {
        $result = $this->loginService->execute(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'data' => [
                'access_token' => $result['access_token'],
                'token_type' => $result['token_type'],
                'user' => new UserResource($result['user']),
            ],
        ]);
    }
}