<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UnlockScooterRequest;
use App\Http\Resources\RideResource;
use App\Models\Scooter;
use App\Services\Ride\RideService;

class UnlockScooterController extends Controller
{
    public function __construct(
        private readonly RideService $rideService,
    ) {
    }

    public function __invoke(
        UnlockScooterRequest $request,
        Scooter $scooter
    ): RideResource {

        $ride = $this->rideService->startRide(
            $request->user(),
            $scooter
        );

        return new RideResource($ride);
    }
}