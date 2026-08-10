<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateScooterLocationRequest;
use App\Http\Resources\ScooterResource;
use App\Models\Scooter;
use App\Services\Scooter\ScooterService;

class ScooterLocationController extends Controller
{
    public function __construct(
        private readonly ScooterService $scooterService,
    ) {
    }

    public function __invoke(
        UpdateScooterLocationRequest $request,
        Scooter $scooter
    ): ScooterResource {

        $scooter = $this->scooterService->updateLocation(
            $scooter,
            $request->validated()
        );

        return new ScooterResource($scooter);
    }
}