<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('label');
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('label');
            $table->timestamps();
        });

        Schema::create('permission_role', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->primary(['role_id', 'permission_id']);
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->primary(['role_id', 'user_id']);
        });

        Schema::create('taxonomy_terms', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('slug')->unique();
            $table->string('label');
            $table->jsonb('meta')->nullable();
            $table->timestamps();
        });

        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->unique();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('category');
            $table->string('body_part')->index();
            $table->string('equipment')->index();
            $table->string('target')->index();
            $table->string('muscle_group')->index();
            $table->jsonb('secondary_muscles')->nullable();
            $table->string('status')->default('draft')->index();
            $table->string('source_checksum')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('exercise_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercise_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 10);
            $table->string('name_override')->nullable();
            $table->text('instructions');
            $table->jsonb('instruction_steps');
            $table->timestamps();
            $table->unique(['exercise_id', 'locale']);
        });

        Schema::create('exercise_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exercise_id')->constrained()->cascadeOnDelete();
            $table->string('kind');
            $table->string('path');
            $table->string('media_id')->nullable();
            $table->string('source_url')->nullable();
            $table->string('attribution');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exercise_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'exercise_id']);
        });

        Schema::create('workout_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status')->default('draft');
            $table->string('locale', 10)->default('es');
            $table->timestamps();
        });

        Schema::create('workout_plan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workout_plan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exercise_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('order_index')->default(0);
            $table->unsignedInteger('sets')->nullable();
            $table->unsignedInteger('reps')->nullable();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->unsignedInteger('rest_seconds')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('workout_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('workout_plan_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('workout_session_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workout_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exercise_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('order_index')->default(0);
            $table->unsignedInteger('sets_completed')->nullable();
            $table->unsignedInteger('reps_completed')->nullable();
            $table->decimal('load_kg', 8, 2)->nullable();
            $table->unsignedTinyInteger('rpe')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('import_batches', function (Blueprint $table) {
            $table->id();
            $table->string('source_name');
            $table->string('status')->default('queued');
            $table->unsignedInteger('total_records')->default(0);
            $table->unsignedInteger('imported_records')->default(0);
            $table->unsignedInteger('failed_records')->default(0);
            $table->jsonb('payload')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->string('action');
            $table->jsonb('before')->nullable();
            $table->jsonb('after')->nullable();
            $table->jsonb('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('import_batches');
        Schema::dropIfExists('workout_session_items');
        Schema::dropIfExists('workout_sessions');
        Schema::dropIfExists('workout_plan_items');
        Schema::dropIfExists('workout_plans');
        Schema::dropIfExists('favorites');
        Schema::dropIfExists('exercise_media');
        Schema::dropIfExists('exercise_translations');
        Schema::dropIfExists('exercises');
        Schema::dropIfExists('taxonomy_terms');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
