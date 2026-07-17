<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\WorkoutPlanResource;
use App\Models\WorkoutPlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkoutPlanController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', WorkoutPlan::class);

        $plans = WorkoutPlan::query()
            ->with('items')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'data' => WorkoutPlanResource::collection($plans),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', WorkoutPlan::class);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'locale' => ['nullable', 'string', 'max:10'],
        ]);

        $plan = WorkoutPlan::create([
            'user_id' => $request->user()->id,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'status' => 'draft',
            'locale' => $data['locale'] ?? 'es',
        ]);

        return response()->json([
            'data' => new WorkoutPlanResource($plan->load('items')),
        ], 201);
    }

    public function show(Request $request, string $workoutPlan): JsonResponse
    {
        $plan = WorkoutPlan::query()
            ->with('items')
            ->where('user_id', $request->user()->id)
            ->findOrFail($workoutPlan);

        $this->authorize('view', $plan);

        return response()->json([
            'data' => new WorkoutPlanResource($plan),
        ]);
    }

    public function update(Request $request, string $workoutPlan): JsonResponse
    {
        $plan = WorkoutPlan::query()
            ->where('user_id', $request->user()->id)
            ->findOrFail($workoutPlan);

        $this->authorize('update', $plan);

        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['sometimes', 'string', 'in:draft,review,published,archived'],
            'locale' => ['sometimes', 'string', 'max:10'],
        ]);

        $plan->update($data);

        return response()->json([
            'data' => new WorkoutPlanResource($plan->fresh()->load('items')),
        ]);
    }
}
