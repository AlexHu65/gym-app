<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxonomyTerm extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'slug',
        'label',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];
}
