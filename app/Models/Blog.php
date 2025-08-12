<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'title',
        'content',
        'excerpt',
        'author',
        'thumbnail',
        'slug',
        'is_active',
        'order',
        'published_at',
    ];
}
