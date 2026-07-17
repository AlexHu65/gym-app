<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseMediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'kind' => $this->kind,
            'path' => $this->path,
            'media_id' => $this->media_id,
            'source_url' => $this->source_url,
            'attribution' => $this->attribution,
            'sort_order' => $this->sort_order,
        ];
    }
}

