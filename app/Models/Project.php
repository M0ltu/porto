<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'category',
        'year',
        'featured',
        'summary',
        'cover_path',
        'tech',
        'role',
        'problem',
        'goal',
        'features',
        'process',
        'feedback',
        'metrics',
        'demo_url',
        'repo_url',
        'sort_order',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'tech' => 'array',
        'features' => 'array',
        'metrics' => 'array',
    ];

    protected $appends = ['cover', 'gallery'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order');
    }

    public function getCoverAttribute(): ?string
    {
        return $this->cover_path;
    }

    public function getGalleryAttribute(): array
    {
        if ($this->relationLoaded('images')) {
            return $this->images->pluck('path')->all();
        }

        return $this->images()->pluck('path')->all();
    }
}
