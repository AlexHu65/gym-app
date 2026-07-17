<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateExerciseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'category' => ['sometimes', 'string', 'max:255'],
            'body_part' => ['sometimes', 'string', 'max:255'],
            'equipment' => ['sometimes', 'string', 'max:255'],
            'target' => ['sometimes', 'string', 'max:255'],
            'muscle_group' => ['sometimes', 'string', 'max:255'],
            'secondary_muscles' => ['sometimes', 'array'],
            'status' => ['sometimes', Rule::in(['draft', 'review', 'published', 'archived'])],
        ];
    }
}

