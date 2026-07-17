<?php

namespace Database\Seeders;

use App\Services\ExerciseImportService;
use Illuminate\Database\Seeder;

class ExerciseDatasetSeeder extends Seeder
{
    public function run(ExerciseImportService $service): void
    {
        $path = base_path('../exercises-dataset/data/exercises.json');

        if (! file_exists($path)) {
            $this->command?->warn('Dataset file not found: ' . $path);
            return;
        }

        $service->importFromPath($path, 'exercises-dataset');
    }
}
