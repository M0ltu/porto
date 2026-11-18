<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'period',
        'title',
        'organization',
        'type',
        'description',
        'tags',
        'current',
        'sort_order',
    ];

    protected $casts = [
        'tags' => 'array',
        'current' => 'boolean',
    ];
}
