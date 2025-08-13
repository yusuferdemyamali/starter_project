<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class BlogCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'blog_category_id');
    }

    protected static function booted()
{
    static::creating(function ($category) {
        if (empty($category->slug) && !empty($category->name)) {
            $category->slug = \Str::slug($category->name);
        }
    });
}
    public function scopeActive($query)
    {
        return $query;
    }
}
