<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\MeController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\ScooterController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::prefix('auth')->group(function () {

        Route::post('/register', RegisterController::class);
        Route::post('/login', LoginController::class);

        Route::middleware('auth:sanctum')->group(function () {

            Route::post('/logout', LogoutController::class);
            Route::get('/me', MeController::class);

        });

    });

    // Protected business APIs
    Route::middleware('auth:sanctum')->group(function () {

        Route::apiResource('scooters', ScooterController::class)
            ->parameters([
                'scooters' => 'scooter:uuid',
            ]);

    });

});
