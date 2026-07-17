<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkoutPlanItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'workout_plan_id',
        'exercise_id',
        'order_index',
        'sets',
        'reps',
        'duration_seconds',
        'rest_seconds',
        'notes',
    ];
}

