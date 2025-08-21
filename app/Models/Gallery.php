<?php

namespace App\Models;

use App\Services\CacheService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\InteractsWithMedia;

class Gallery extends Model
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'user_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Eager loading for performance
    protected $with = ['user'];

    /**
     * Gallery'nin sahibi (user)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: Aktif galeri öğeleri
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Cache'li aktif galeri öğelerini getir
     */
    public static function getCachedActiveGallery(int $limit = 20)
    {
        $key = CacheService::generateKey('active_gallery', $limit);

        return CacheService::remember(
            $key,
            CacheService::DEFAULT_TTL,
            function () use ($limit) {
                return static::with('user')
                    ->active()
                    ->orderBy('created_at', 'desc')
                    ->limit($limit)
                    ->get();
            }
        );
    }

    /**
     * Cache'li galeri detayını getir
     */
    public static function getCachedGalleryBySlug(string $slug)
    {
        $key = CacheService::generateKey('gallery_by_slug', $slug);

        return CacheService::remember(
            $key,
            CacheService::LONG_TTL,
            function () use ($slug) {
                return static::with('user')
                    ->where('slug', $slug)
                    ->active()
                    ->first();
            }
        );
    }

    /**
     * Cache'li en son galeri öğelerini getir
     */
    public static function getCachedLatestGallery(int $limit = 12)
    {
        $key = CacheService::generateKey('latest_gallery', $limit);

        return CacheService::remember(
            $key,
            CacheService::SHORT_TTL,
            function () use ($limit) {
                return static::with('user')
                    ->active()
                    ->orderBy('created_at', 'desc')
                    ->limit($limit)
                    ->get();
            }
        );
    }
}
