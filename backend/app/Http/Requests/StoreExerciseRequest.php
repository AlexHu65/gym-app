<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExerciseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'external_id' => ['required', 'string', 'max:32', 'unique:exercises,external_id'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'body_part' => ['required', 'string', 'max:255'],
            'equipment' => ['required', 'string', 'max:255'],
            'target' => ['required', 'string', 'max:255'],
            'muscle_group' => ['required', 'string', 'max:255'],
            'secondary_muscles' => ['nullable', 'array'],
            'status' => ['sometimes', 'string', 'in:draft,review,published,archived'],
        ];
    }
}

