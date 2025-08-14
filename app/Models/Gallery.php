<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Gallery extends Model
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'user_id',
    ];
}
