<?php

namespace App\Policies;

use App\Models\Exercise;
use App\Models\User;

class ExercisePolicy
{
    public function viewAny(?User $user = null): bool
    {
        return true;
    }

    public function view(?User $user, Exercise $exercise): bool
    {
        if ($exercise->status === 'published') {
            return true;
        }

        return $user?->hasPermission('manage-exercises') ?? false;
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('manage-exercises');
    }

    public function update(User $user, Exercise $exercise): bool
    {
        return $user->hasPermission('manage-exercises');
    }

    public function delete(User $user, Exercise $exercise): bool
    {
        return $user->hasPermission('manage-exercises');
    }

    public function publish(User $user, Exercise $exercise): bool
    {
        return $user->hasPermission('manage-exercises');
    }
}

