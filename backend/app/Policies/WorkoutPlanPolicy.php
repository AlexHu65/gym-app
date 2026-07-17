<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkoutPlan;

class WorkoutPlanPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, WorkoutPlan $workoutPlan): bool
    {
        return $workoutPlan->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, WorkoutPlan $workoutPlan): bool
    {
        return $workoutPlan->user_id === $user->id;
    }
}
