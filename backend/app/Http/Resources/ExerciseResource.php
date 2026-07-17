<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->external_id,
            'internal_id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'category' => $this->category,
            'body_part' => $this->body_part,
            'equipment' => $this->equipment,
            'target' => $this->target,
            'muscle_group' => $this->muscle_group,
            'secondary_muscles' => $this->secondary_muscles ?? [],
            'status' => $this->status,
            'translation' => $this->when(
                $this->relationLoaded('selectedTranslation') && $this->selectedTranslation,
                fn () => new ExerciseTranslationResource($this->selectedTranslation)
            ),
            'translations' => ExerciseTranslationResource::collection($this->whenLoaded('translations')),
            'media' => ExerciseMediaResource::collection($this->whenLoaded('media')),
        ];
    }
}

