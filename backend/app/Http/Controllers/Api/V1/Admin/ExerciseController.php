<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExerciseRequest;
use App\Http\Requests\UpdateExerciseRequest;
use App\Http\Resources\ExerciseResource;
use App\Models\AuditLog;
use App\Models\Exercise;
use App\Services\ExerciseImportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Exercise::class);

        $query = Exercise::query()
            ->with(['translations', 'media'])
            ->when($request->filled('status'), fn ($builder) => $builder->where('status', $request->string('status')))
            ->when($request->filled('search'), function ($builder) use ($request): void {
                $search = trim((string) $request->string('search'));
                $builder->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name');

        return response()->json([
            'data' => ExerciseResource::collection(
                $query->paginate(min((int) $request->integer('per_page', 20), 100))
            ),
        ]);
    }

    public function store(StoreExerciseRequest $request): JsonResponse
    {
        $this->authorize('create', Exercise::class);

        $exercise = Exercise::create($request->safe()->only([
            'external_id',
            'name',
            'category',
            'body_part',
            'equipment',
            'target',
            'muscle_group',
            'secondary_muscles',
            'status',
        ]));

        return response()->json([
            'data' => new ExerciseResource($exercise->load(['translations', 'media'])),
        ], 201);
    }

    public function update(UpdateExerciseRequest $request, Exercise $exercise): JsonResponse
    {
        $this->authorize('update', $exercise);

        $before = $exercise->replicate()->toArray();

        $exercise->update($request->safe()->only([
            'name',
            'category',
            'body_part',
            'equipment',
            'target',
            'muscle_group',
            'secondary_muscles',
            'status',
        ]));

        AuditLog::create([
            'actor_id' => $request->user()->id,
            'entity_type' => Exercise::class,
            'entity_id' => $exercise->id,
            'action' => 'updated',
            'before' => $before,
            'after' => $exercise->fresh()->toArray(),
            'meta' => [],
        ]);

        return response()->json([
            'data' => new ExerciseResource($exercise->fresh()->load(['translations', 'media'])),
        ]);
    }

    public function import(Request $request, ExerciseImportService $service): JsonResponse
    {
        $this->authorize('create', Exercise::class);

        $result = $service->importFromPath(
            base_path('../exercises-dataset/data/exercises.json'),
            'exercises-dataset'
        );

        return response()->json($result, 202);
    }

    public function auditLogs(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Exercise::class);

        return response()->json([
            'data' => AuditLog::query()->latest()->limit(50)->get(),
        ]);
    }
}
