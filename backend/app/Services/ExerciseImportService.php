<?php

namespace App\Services;

use App\Models\Exercise;
use App\Models\ExerciseMedia;
use App\Models\ExerciseTranslation;
use App\Models\ImportBatch;
use App\Models\TaxonomyTerm;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ExerciseImportService
{
    public function importFromPath(string $path, string $sourceName = 'exercises-dataset'): array
    {
        if (! file_exists($path)) {
            throw new \RuntimeException("Dataset file not found: {$path}");
        }

        $records = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
        $batch = ImportBatch::create([
            'source_name' => $sourceName,
            'status' => 'running',
            'total_records' => count($records),
            'started_at' => now(),
            'payload' => [
                'path' => $path,
            ],
        ]);

        $imported = 0;
        $failed = 0;

        foreach ($records as $record) {
            try {
                DB::transaction(function () use ($record): void {
                    $exercise = Exercise::updateOrCreate(
                        ['external_id' => $record['id']],
                        [
                            'slug' => Str::slug($record['name']) . '-' . $record['id'],
                            'name' => $record['name'],
                            'category' => $record['category'],
                            'body_part' => $record['body_part'],
                            'equipment' => $record['equipment'],
                            'target' => $record['target'],
                            'muscle_group' => $record['muscle_group'],
                            'secondary_muscles' => $record['secondary_muscles'] ?? [],
                            'status' => 'published',
                            'source_checksum' => sha1(json_encode($record)),
                        ]
                    );

                    foreach (($record['instructions'] ?? []) as $locale => $instructions) {
                        ExerciseTranslation::updateOrCreate(
                            [
                                'exercise_id' => $exercise->id,
                                'locale' => $locale,
                            ],
                            [
                                'name_override' => null,
                                'instructions' => $instructions,
                                'instruction_steps' => $record['instruction_steps'][$locale] ?? [],
                            ]
                        );
                    }

                    foreach ([
                        'thumbnail' => $record['image'] ?? null,
                        'animation' => $record['gif_url'] ?? null,
                    ] as $kind => $path) {
                        if (! $path) {
                            continue;
                        }

                        ExerciseMedia::updateOrCreate(
                            [
                                'exercise_id' => $exercise->id,
                                'kind' => $kind,
                            ],
                            [
                                'path' => $path,
                                'media_id' => $record['media_id'] ?? null,
                                'source_url' => null,
                                'attribution' => $record['attribution'] ?? '© Gym visual — https://gymvisual.com/',
                                'sort_order' => $kind === 'thumbnail' ? 0 : 1,
                            ]
                        );
                    }

                    $this->seedTaxonomy('body_part', $record['body_part']);
                    $this->seedTaxonomy('equipment', $record['equipment']);
                    $this->seedTaxonomy('target', $record['target']);
                    $this->seedTaxonomy('muscle_group', $record['muscle_group']);

                    foreach ($record['secondary_muscles'] ?? [] as $secondaryMuscle) {
                        $this->seedTaxonomy('secondary_muscle', $secondaryMuscle);
                    }
                });

                $imported++;
            } catch (\Throwable $e) {
                report($e);
                $failed++;
            }
        }

        $batch->update([
            'status' => $failed > 0 ? 'completed_with_errors' : 'completed',
            'imported_records' => $imported,
            'failed_records' => $failed,
            'finished_at' => now(),
        ]);

        return [
            'batch_id' => $batch->id,
            'total_records' => $batch->total_records,
            'imported_records' => $imported,
            'failed_records' => $failed,
        ];
    }

    private function seedTaxonomy(string $type, string $label): void
    {
        TaxonomyTerm::updateOrCreate(
            [
                'slug' => Str::slug($type . '-' . $label),
            ],
            [
                'type' => $type,
                'label' => $label,
                'meta' => [],
            ]
        );
    }
}
