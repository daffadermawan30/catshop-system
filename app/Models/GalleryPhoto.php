<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryPhoto extends Model
{
    protected $fillable = ['photo', 'caption', 'type', 'is_active', 'sort_order'];

    protected $casts = ['is_active' => 'boolean'];
}
