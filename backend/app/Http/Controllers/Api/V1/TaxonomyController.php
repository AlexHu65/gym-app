<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\TaxonomyTerm;
use Illuminate\Http\JsonResponse;

class TaxonomyController extends Controller
{
    public function index(): JsonResponse
    {
        $grouped = TaxonomyTerm::query()
            ->orderBy('type')
            ->orderBy('label')
            ->get()
            ->groupBy('type')
            ->map(fn ($items) => $items->map(fn (TaxonomyTerm $term) => [
                'slug' => $term->slug,
                'label' => $term->label,
            ])->values());

        return response()->json([
            'data' => $grouped,
        ]);
    }
}

