<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;



class Blog extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use HasTags;


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

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }
}
