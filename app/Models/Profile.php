<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'name',
        'nickname',
        'school_class',
        'major',
        'headline',
        'bio',
        'location',
        'email',
        'availability',
        'avatar_path',
        'cv_path',
        'education',
        'interests',
        'values',
        'goals',
    ];

    protected $casts = [
        'education' => 'array',
        'interests' => 'array',
        'values' => 'array',
    ];

    protected $appends = ['avatar'];

    public function getAvatarAttribute(): ?string
    {
        return $this->avatar_path;
    }
}
