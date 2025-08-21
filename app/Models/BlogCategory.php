<?php

namespace App\Models;

use App\Services\CacheService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'slug',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Kategoriye ait bloglar
     */
    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class, 'blog_category_id');
    }

    /**
     * Aktif bloglar
     */
    public function activeBlogs(): HasMany
    {
        return $this->blogs()->where('is_active', true);
    }

    /**
     * Yayınlanan bloglar
     */
    public function publishedBlogs(): HasMany
    {
        return $this->blogs()
            ->where('is_active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope: Aktif kategoriler
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Blog sayısına göre sırala
     */
    public function scopeWithBlogCount(Builder $query): Builder
    {
        return $query->withCount(['blogs as blogs_count']);
    }

    /**
     * Cache'li tüm aktif kategorileri getir
     */
    public static function getCachedActiveCategories()
    {
        $key = CacheService::generateKey('active_blog_categories');
        
        return CacheService::remember(
            $key,
            CacheService::LONG_TTL,
            function () {
                return static::active()
                    ->withBlogCount()
                    ->orderBy('name')
                    ->get();
            }
        );
    }

    /**
     * Cache'li kategori ile bloglarını getir
     */
    public function getCachedBlogsWithPagination(int $perPage = 10)
    {
        $key = CacheService::generateKey('category_blogs_paginated', $this->id, $perPage, request('page', 1));
        
        return CacheService::remember(
            $key,
            CacheService::SHORT_TTL, // Pagination için kısa cache
            function () use ($perPage) {
                return $this->publishedBlogs()
                    ->with('category')
                    ->orderBy('order', 'asc')
                    ->orderBy('created_at', 'desc')
                    ->paginate($perPage);
            }
        );
    }

    /**
     * Slug yaratma
     */
    protected static function booted()
    {
        static::creating(function ($category) {
            if (empty($category->slug) && !empty($category->name)) {
                $category->slug = \Str::slug($category->name);
            }
            
            // is_active default değeri
            if ($category->is_active === null) {
                $category->is_active = true;
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = \Str::slug($category->name);
            }
        });
    }
}
