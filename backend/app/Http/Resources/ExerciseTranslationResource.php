<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseTranslationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'locale' => $this->locale,
            'name' => $this->name_override ?: null,
            'instructions' => $this->instructions,
            'instruction_steps' => $this->instruction_steps,
        ];
    }
}

