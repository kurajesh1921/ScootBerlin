<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScooterRequest;
use App\Http\Requests\UpdateScooterRequest;
use App\Http\Resources\ScooterResource;
use App\Models\Scooter;
use App\Services\Scooter\ScooterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ScooterController extends Controller
{
    public function __construct(
        private readonly ScooterService $scooterService
    ) {}

    public function index(): AnonymousResourceCollection
    {
        $scooters = $this->scooterService->getAll();

        return ScooterResource::collection($scooters);
    }

    public function store(StoreScooterRequest $request): JsonResponse
    {
        $scooter = $this->scooterService->create(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Scooter created successfully.',
            'data' => new ScooterResource($scooter),
        ], 201);
    }

    public function show(Scooter $scooter): JsonResponse
    {
        $scooter->load('status');

        return response()->json([
            'success' => true,
            'data' => new ScooterResource($scooter),
        ]);
    }

    public function update(
        UpdateScooterRequest $request,
        Scooter $scooter
    ): JsonResponse {
        $scooter = $this->scooterService->update(
            $scooter,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Scooter updated successfully.',
            'data' => new ScooterResource($scooter),
        ]);
    }

    public function destroy(Scooter $scooter): JsonResponse
    {
        $this->scooterService->delete($scooter);

        return response()->json([
            'success' => true,
            'message' => 'Scooter deleted successfully.',
        ]);
    }
}
