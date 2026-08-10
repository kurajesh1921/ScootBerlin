<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReserveScooterRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Scooter;
use App\Services\Reservation\ReservationService;
use Illuminate\Http\Response;

class ReserveScooterController extends Controller
{
    public function __construct(
        private readonly ReservationService $reservationService,
    ) {
    }

    public function __invoke(
        ReserveScooterRequest $request,
        Scooter $scooter
    ): ReservationResource {

        $reservation = $this->reservationService->reserve(
            $request->user(),
            $scooter
        );

        return new ReservationResource($reservation);
    }
}