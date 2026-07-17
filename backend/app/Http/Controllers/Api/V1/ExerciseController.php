<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExerciseResource;
use App\Models\Exercise;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    public function index(Request $request)
    {
        $locale = $request->string('locale', 'es')->toString() ?: 'es';

        $query = Exercise::query()
            ->where('status', 'published')
            ->when($request->filled('search'), function ($builder) use ($request): void {
                $search = trim((string) $request->string('search'));
                $builder->where('name', 'like', "%{$search}%");
            })
            ->when($request->filled('body_part'), fn ($builder) => $builder->where('body_part', $request->string('body_part')))
            ->when($request->filled('equipment'), fn ($builder) => $builder->where('equipment', $request->string('equipment')))
            ->when($request->filled('target'), fn ($builder) => $builder->where('target', $request->string('target')))
            ->with([
                'translations' => fn ($builder) => $builder->where('locale', $locale),
                'media' => fn ($builder) => $builder->orderBy('sort_order'),
            ])
            ->orderBy('name');

        /** @var LengthAwarePaginator $paginator */
        $paginator = $query->paginate(min((int) $request->integer('per_page', 20), 100));

        $paginator->getCollection()->transform(function (Exercise $exercise) {
            $exercise->setRelation('selectedTranslation', $exercise->translations->first());

            return $exercise;
        });

        return ExerciseResource::collection($paginator);
    }

    public function show(Exercise $exercise, Request $request): ExerciseResource
    {
        abort_unless($exercise->status === 'published', 404);

        $locale = $request->string('locale', 'es')->toString() ?: 'es';

        $exercise->load([
            'translations',
            'media' => fn ($builder) => $builder->orderBy('sort_order'),
        ]);

        $exercise->setRelation(
            'selectedTranslation',
            $exercise->translations->firstWhere('locale', $locale) ?? $exercise->translations->first()
        );

        return new ExerciseResource($exercise);
    }
}
