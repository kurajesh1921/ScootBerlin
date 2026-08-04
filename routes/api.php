<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\MeController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\ScooterController;
use App\Http\Controllers\Api\V1\ScooterLocationController;
use App\Http\Controllers\Api\V1\ReserveScooterController;
use App\Http\Controllers\Api\V1\UnlockScooterController;
use App\Http\Controllers\Api\V1\EndRideController;

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
    Route::get(
        'scooters/nearby',
        [ScooterController::class, 'nearby']
    );
    Route::apiResource('scooters', ScooterController::class)
    ->parameters([
        'scooters' => 'scooter:uuid',
    ]);
    Route::post(
        'scooters/{scooter:uuid}/reserve',
        ReserveScooterController::class
    );
        
    Route::post(
        '/scooters/{scooter:uuid}/location',
        ScooterLocationController::class
    );
    Route::post(
    'scooters/{scooter:uuid}/unlock',
        UnlockScooterController::class
    );
    Route::post(
        'rides/{ride:uuid}/end',
        EndRideController::class
    );

    });
    

});
