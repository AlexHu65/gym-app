<?php

namespace App\Providers;

use App\Models\Exercise;
use App\Models\User;
use App\Models\WorkoutPlan;
use App\Policies\ExercisePolicy;
use App\Policies\WorkoutPlanPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Exercise::class, ExercisePolicy::class);
        Gate::policy(WorkoutPlan::class, WorkoutPlanPolicy::class);

        Gate::before(function (User $user, string $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });
    }
}

