<?php

namespace App\Models;

use App\Services\CacheService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
        'color',
        'icon',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Kategoriye ait ürünler
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    /**
     * Aktif ürünler
     */
    public function activeProducts(): HasMany
    {
        return $this->products()->where('is_active', true);
    }

    /**
     * Scope: Aktif kategoriler
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Sıraya göre
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc');
    }

    /**
     * Scope: Ürün sayısına göre
     */
    public function scopeWithProductCount(Builder $query): Builder
    {
        return $query->withCount(['activeProducts as active_products_count']);
    }

    /**
     * Cache'li tüm aktif kategorileri getir
     */
    public static function getCachedActiveCategories()
    {
        $key = CacheService::generateKey('active_product_categories');

        return CacheService::remember(
            $key,
            CacheService::LONG_TTL,
            function () {
                return static::active()
                    ->withProductCount()
                    ->ordered()
                    ->get();
            }
        );
    }

    /**
     * Cache'li kategori ile ürünlerini getir (pagination ile)
     */
    public function getCachedProductsWithPagination(int $perPage = 12)
    {
        $key = CacheService::generateKey('category_products_paginated', $this->id, $perPage, request('page', 1));

        return CacheService::remember(
            $key,
            CacheService::SHORT_TTL, // Pagination için kısa cache
            function () use ($perPage) {
                return $this->activeProducts()
                    ->with('category')
                    ->orderBy('name')
                    ->paginate($perPage);
            }
        );
    }

    /**
     * Cache'li aktif ürün sayısını getir
     */
    public function getCachedActiveProductsCount(): int
    {
        $key = CacheService::generateKey('category_active_products_count', $this->id);

        return CacheService::remember(
            $key,
            CacheService::DEFAULT_TTL,
            function () {
                return $this->activeProducts()->count();
            }
        );
    }

    /**
     * Cache'li en popüler kategoriler (en çok ürünlü)
     */
    public static function getCachedPopularCategories(int $limit = 6)
    {
        $key = CacheService::generateKey('popular_product_categories', $limit);

        return CacheService::remember(
            $key,
            CacheService::LONG_TTL,
            function () use ($limit) {
                return static::active()
                    ->withProductCount()
                    ->having('active_products_count', '>', 0)
                    ->orderBy('active_products_count', 'desc')
                    ->limit($limit)
                    ->get();
            }
        );
    }
}
