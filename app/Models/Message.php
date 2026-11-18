<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'email',
        'relation',
        'subject',
        'body',
        'ratings',
        'approved',
    ];

    protected $casts = [
        'ratings' => 'array',
        'approved' => 'boolean',
    ];

    protected $appends = ['date'];

    public function getDateAttribute(): ?string
    {
        return $this->created_at?->format('d M Y');
    }
}
