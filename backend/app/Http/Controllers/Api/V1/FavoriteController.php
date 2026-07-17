<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExerciseResource;
use App\Models\Exercise;
use App\Models\Favorite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $favorites = Favorite::query()
            ->with(['exercise.translations', 'exercise.media'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'data' => $favorites->map(function (Favorite $favorite) use ($request) {
                $exercise = $favorite->exercise;
                $locale = $request->string('locale', 'es')->toString() ?: 'es';
                $exercise->setRelation(
                    'selectedTranslation',
                    $exercise->translations->firstWhere('locale', $locale) ?? $exercise->translations->first()
                );

                return [
                    'id' => $favorite->id,
                    'exercise' => new ExerciseResource($exercise),
                ];
            })->values(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'exercise_id' => ['required', 'string'],
        ]);

        $exercise = Exercise::where('external_id', $data['exercise_id'])->firstOrFail();

        $favorite = Favorite::firstOrCreate([
            'user_id' => $request->user()->id,
            'exercise_id' => $exercise->id,
        ]);

        return response()->json([
            'message' => $favorite->wasRecentlyCreated ? 'Favorite stored.' : 'Favorite already exists.',
        ], $favorite->wasRecentlyCreated ? 201 : 200);
    }

    public function destroy(Request $request, string $favorite): JsonResponse
    {
        Favorite::query()
            ->where('id', $favorite)
            ->where('user_id', $request->user()->id)
            ->delete();

        return response()->json([
            'message' => 'Favorite deleted.',
        ]);
    }
}

