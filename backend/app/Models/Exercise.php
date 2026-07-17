<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exercise extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'external_id',
        'slug',
        'name',
        'category',
        'body_part',
        'equipment',
        'target',
        'muscle_group',
        'secondary_muscles',
        'status',
        'source_checksum',
    ];

    protected $casts = [
        'secondary_muscles' => 'array',
    ];

    public function getRouteKeyName(): string
    {
        return 'external_id';
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ExerciseTranslation::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(ExerciseMedia::class);
    }
}
