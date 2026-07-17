<?php

use App\Http\Controllers\Api\V1\Admin\ExerciseController as AdminExerciseController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ExerciseController;
use App\Http\Controllers\Api\V1\FavoriteController;
use App\Http\Controllers\Api\V1\HealthController;
use App\Http\Controllers\Api\V1\TaxonomyController;
use App\Http\Controllers\Api\V1\WorkoutPlanController;
use App\Http\Controllers\Api\V1\WorkoutSessionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/health', HealthController::class);

    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');

    Route::get('/exercises', [ExerciseController::class, 'index']);
    Route::get('/exercises/{exercise}', [ExerciseController::class, 'show']);
    Route::get('/taxonomies', [TaxonomyController::class, 'index']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/favorites', [FavoriteController::class, 'index']);
        Route::post('/favorites', [FavoriteController::class, 'store']);
        Route::delete('/favorites/{favorite}', [FavoriteController::class, 'destroy']);

        Route::get('/workout-plans', [WorkoutPlanController::class, 'index']);
        Route::post('/workout-plans', [WorkoutPlanController::class, 'store']);
        Route::get('/workout-plans/{workoutPlan}', [WorkoutPlanController::class, 'show']);
        Route::patch('/workout-plans/{workoutPlan}', [WorkoutPlanController::class, 'update']);

        Route::get('/workout-sessions', [WorkoutSessionController::class, 'index']);
        Route::post('/workout-sessions', [WorkoutSessionController::class, 'store']);
        Route::get('/workout-sessions/{workoutSession}', [WorkoutSessionController::class, 'show']);

        Route::prefix('admin')->group(function () {
            Route::get('/exercises', [AdminExerciseController::class, 'index']);
            Route::post('/exercises', [AdminExerciseController::class, 'store']);
            Route::patch('/exercises/{exercise}', [AdminExerciseController::class, 'update']);
            Route::post('/imports/exercises', [AdminExerciseController::class, 'import']);
            Route::get('/audit-logs', [AdminExerciseController::class, 'auditLogs']);
        });
    });
});

