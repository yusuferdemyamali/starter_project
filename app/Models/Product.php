<?php

namespace App\Models;

use App\Services\CacheService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'category_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    // Eager loading - performance için
    protected $with = ['category'];

    /**
     * Ürünün kategorisi
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    /**
     * Scope: Aktif ürünler
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Fiyat aralığına göre
     */
    public function scopePriceBetween(Builder $query, float $min, float $max): Builder
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    /**
     * Scope: Kategoriye göre
     */
    public function scopeByCategory(Builder $query, int $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope: Fiyata göre sırala
     */
    public function scopeOrderByPrice(Builder $query, string $direction = 'asc'): Builder
    {
        return $query->orderBy('price', $direction);
    }

    /**
     * Cache'li tüm aktif ürünleri getir
     */
    public static function getCachedActiveProducts(int $limit = 20)
    {
        $key = CacheService::generateKey('active_products', $limit);

        return CacheService::remember(
            $key,
            CacheService::DEFAULT_TTL,
            function () use ($limit) {
                return static::with('category')
                    ->active()
                    ->orderBy('name')
                    ->limit($limit)
                    ->get();
            }
        );
    }

    /**
     * Cache'li kategoriye göre ürünler
     */
    public static function getCachedProductsByCategory(int $categoryId, int $limit = 20)
    {
        $key = CacheService::generateKey('products_by_category', $categoryId, $limit);

        return CacheService::remember(
            $key,
            CacheService::DEFAULT_TTL,
            function () use ($categoryId, $limit) {
                return static::with('category')
                    ->byCategory($categoryId)
                    ->active()
                    ->orderBy('name')
                    ->limit($limit)
                    ->get();
            }
        );
    }

    /**
     * Cache'li fiyat aralığına göre ürünler
     */
    public static function getCachedProductsByPriceRange(float $min, float $max, int $limit = 20)
    {
        $key = CacheService::generateKey('products_price_range', $min, $max, $limit);

        return CacheService::remember(
            $key,
            CacheService::DEFAULT_TTL,
            function () use ($min, $max, $limit) {
                return static::with('category')
                    ->active()
                    ->priceBetween($min, $max)
                    ->orderByPrice()
                    ->limit($limit)
                    ->get();
            }
        );
    }

    /**
     * Cache'li öne çıkan ürünler (en pahalı)
     */
    public static function getCachedFeaturedProducts(int $limit = 8)
    {
        $key = CacheService::generateKey('featured_products', $limit);

        return CacheService::remember(
            $key,
            CacheService::LONG_TTL,
            function () use ($limit) {
                return static::with('category')
                    ->active()
                    ->orderByPrice('desc')
                    ->limit($limit)
                    ->get();
            }
        );
    }

    /**
     * Cache'li benzer ürünler
     */
    public function getCachedRelatedProducts(int $limit = 4)
    {
        $key = CacheService::generateKey('related_products', $this->id, $this->category_id, $limit);

        return CacheService::remember(
            $key,
            CacheService::DEFAULT_TTL,
            function () use ($limit) {
                return static::with('category')
                    ->byCategory($this->category_id)
                    ->where('id', '!=', $this->id)
                    ->active()
                    ->orderBy('name')
                    ->limit($limit)
                    ->get();
            }
        );
    }
}
