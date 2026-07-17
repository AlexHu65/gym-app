<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExerciseTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'exercise_id',
        'locale',
        'name_override',
        'instructions',
        'instruction_steps',
    ];

    protected $casts = [
        'instruction_steps' => 'array',
    ];

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }
}
