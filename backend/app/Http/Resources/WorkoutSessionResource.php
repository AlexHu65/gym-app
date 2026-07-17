<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkoutSessionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'started_at' => $this->started_at,
            'finished_at' => $this->finished_at,
            'notes' => $this->notes,
            'items' => $this->whenLoaded('items'),
        ];
    }
}

