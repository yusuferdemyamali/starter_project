<?php

namespace App\Models;

use App\Services\CacheService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

class Blog extends Model implements HasMedia
{
    use HasFactory;
    use HasTags;
    use InteractsWithMedia;

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
        'blog_category_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'published_at' => 'datetime',
        'order' => 'integer',
    ];

    // Eager loading - performance için
    protected $with = ['category'];

    /**
     * Blog kategorisi
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    /**
     * Scope: Aktif bloglar
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Yayınlanan bloglar
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_active', true)
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope: Sıraya göre
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order', 'asc')->orderBy('created_at', 'desc');
    }

    /**
     * Scope: Son bloglar
     */
    public function scopeLatest(Builder $query, int $limit = 10): Builder
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    /**
     * Cache'li aktif blogları getir
     */
    public static function getCachedActiveBlogs(int $limit = 10)
    {
        $key = CacheService::generateKey('active_blogs', $limit);
        
        return CacheService::remember(
            $key,
            CacheService::DEFAULT_TTL,
            function () use ($limit) {
                return static::with('category')
                    ->published()
                    ->ordered()
                    ->limit($limit)
                    ->get();
            }
        );
    }

    /**
     * Cache'li blog kategorisine göre getir
     */
    public static function getCachedBlogsByCategory(int $categoryId, int $limit = 10)
    {
        $key = CacheService::generateKey('blogs_by_category', $categoryId, $limit);
        
        return CacheService::remember(
            $key,
            CacheService::DEFAULT_TTL,
            function () use ($categoryId, $limit) {
                return static::with('category')
                    ->where('blog_category_id', $categoryId)
                    ->published()
                    ->ordered()
                    ->limit($limit)
                    ->get();
            }
        );
    }

    /**
     * Cache'li tek blog getir
     */
    public static function getCachedBlogBySlug(string $slug)
    {
        $key = CacheService::generateKey('blog_by_slug', $slug);
        
        return CacheService::remember(
            $key,
            CacheService::LONG_TTL,
            function () use ($slug) {
                return static::with(['category', 'tags'])
                    ->where('slug', $slug)
                    ->published()
                    ->first();
            }
        );
    }

    /**
     * Cache'li benzer bloglar getir
     */
    public function getCachedRelatedBlogs(int $limit = 5)
    {
        $key = CacheService::generateKey('related_blogs', $this->id, $this->blog_category_id, $limit);
        
        return CacheService::remember(
            $key,
            CacheService::DEFAULT_TTL,
            function () use ($limit) {
                return static::with('category')
                    ->where('blog_category_id', $this->blog_category_id)
                    ->where('id', '!=', $this->id)
                    ->published()
                    ->ordered()
                    ->limit($limit)
                    ->get();
            }
        );
    }
}
