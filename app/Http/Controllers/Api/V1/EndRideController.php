<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\EndRideRequest;
use App\Http\Resources\RideResource;
use App\Models\Ride;
use App\Services\Ride\RideService;

class EndRideController extends Controller
{
    public function __construct(
        private readonly RideService $rideService,
    ) {
    }

    public function __invoke(
        EndRideRequest $request,
        Ride $ride
    ): RideResource {

        $ride = $this->rideService->endRide(
            $request->user(),
            $ride
        );

        return new RideResource($ride);
    }
}