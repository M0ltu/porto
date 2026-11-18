<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    protected $fillable = ['label', 'icon', 'href', 'external', 'sort_order'];

    protected $casts = ['external' => 'boolean'];
}
