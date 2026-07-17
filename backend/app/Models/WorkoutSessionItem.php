<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkoutSessionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'workout_session_id',
        'exercise_id',
        'order_index',
        'sets_completed',
        'reps_completed',
        'load_kg',
        'rpe',
        'notes',
    ];
}

