<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'issuer',
        'year',
        'type',
        'image_path',
        'description',
        'credential_url',
        'sort_order',
    ];

    protected $appends = ['image'];

    public function getImageAttribute(): ?string
    {
        return $this->image_path;
    }
}
