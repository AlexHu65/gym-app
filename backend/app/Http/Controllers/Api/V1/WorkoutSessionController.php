<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\WorkoutSessionResource;
use App\Models\WorkoutPlan;
use App\Models\WorkoutSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkoutSessionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $sessions = WorkoutSession::query()
            ->with('items')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'data' => WorkoutSessionResource::collection($sessions),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'workout_plan_id' => ['nullable', 'integer'],
            'started_at' => ['nullable', 'date'],
            'finished_at' => ['nullable', 'date', 'after_or_equal:started_at'],
            'notes' => ['nullable', 'string'],
        ]);

        if (! empty($data['workout_plan_id'])) {
            WorkoutPlan::query()
                ->where('user_id', $request->user()->id)
                ->findOrFail($data['workout_plan_id']);
        }

        $session = WorkoutSession::create([
            'user_id' => $request->user()->id,
            'workout_plan_id' => $data['workout_plan_id'] ?? null,
            'started_at' => $data['started_at'] ?? now(),
            'finished_at' => $data['finished_at'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);

        return response()->json([
            'data' => new WorkoutSessionResource($session->load('items')),
        ], 201);
    }

    public function show(Request $request, string $workoutSession): JsonResponse
    {
        $session = WorkoutSession::query()
            ->with('items')
            ->where('user_id', $request->user()->id)
            ->findOrFail($workoutSession);

        return response()->json([
            'data' => new WorkoutSessionResource($session),
        ]);
    }
}

