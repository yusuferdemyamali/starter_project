<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    // Cache TTL süresi (saniye)
    const DEFAULT_TTL = 3600; // 1 saat
    const LONG_TTL = 86400; // 1 gün
    const SHORT_TTL = 300; // 5 dakika

    // Cache prefix'leri
    const BLOG_PREFIX = 'blog';
    const BLOG_CATEGORY_PREFIX = 'blog_category';
    const PRODUCT_PREFIX = 'product';
    const PRODUCT_CATEGORY_PREFIX = 'product_category';

    /**
     * Belirli prefix ile cache'e veri kaydet
     */
    public static function remember(string $key, int $ttl, callable $callback)
    {
        return Cache::remember($key, $ttl, $callback);
    }

    /**
     * Belirli prefix'li cache'leri temizle (key pattern ile)
     */
    public static function forgetByPattern(string $pattern): void
    {
        // Database cache driver için pattern matching
        // Bu basit implementation'dır, production'da redis/memcached kullanın
        $keys = [
            $pattern . '*',
            'blog_*',
            'product_*',
            'category_*'
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Blog ile ilgili cache'leri temizle
     */
    public static function clearBlogCache(?int $blogId = null, ?int $categoryId = null): void
    {
        $patterns = [
            'blog_',
            'active_blogs',
            'blogs_by_category',
            'blog_by_slug'
        ];

        if ($categoryId) {
            $patterns[] = 'blog_category_' . $categoryId;
            $patterns[] = 'category_blogs_paginated_' . $categoryId;
        }

        if ($blogId) {
            $patterns[] = 'blog_' . $blogId;
            $patterns[] = 'related_blogs_' . $blogId;
        }

        foreach ($patterns as $pattern) {
            // Clear multiple variations
            for ($i = 1; $i <= 100; $i++) { // Clear potential variations
                Cache::forget($pattern . '_' . $i);
                Cache::forget($pattern . $i);
            }
            Cache::forget($pattern);
        }
    }

    /**
     * Ürün ile ilgili cache'leri temizle
     */
    public static function clearProductCache(?int $productId = null, ?int $categoryId = null): void
    {
        $patterns = [
            'product_',
            'active_products',
            'products_by_category',
            'products_price_range',
            'featured_products'
        ];

        if ($categoryId) {
            $patterns[] = 'product_category_' . $categoryId;
            $patterns[] = 'category_products_paginated_' . $categoryId;
        }

        if ($productId) {
            $patterns[] = 'product_' . $productId;
            $patterns[] = 'related_products_' . $productId;
        }

        foreach ($patterns as $pattern) {
            // Clear multiple variations
            for ($i = 1; $i <= 200; $i++) { // Clear potential variations
                Cache::forget($pattern . '_' . $i);
                Cache::forget($pattern . $i);
            }
            Cache::forget($pattern);
        }
    }

    /**
     * Blog kategori cache'lerini temizle
     */
    public static function clearBlogCategoryCache(?int $categoryId = null): void
    {
        $patterns = [
            'active_blog_categories',
            'category_blogs_paginated'
        ];
        
        if ($categoryId) {
            $patterns[] = 'blog_category_' . $categoryId;
            $patterns[] = 'category_blogs_paginated_' . $categoryId;
        }

        foreach ($patterns as $pattern) {
            for ($i = 1; $i <= 50; $i++) {
                Cache::forget($pattern . '_' . $i);
                Cache::forget($pattern . $i);
            }
            Cache::forget($pattern);
        }

        // Blog cache'lerini de temizle çünkü bağlantılı
        self::clearBlogCache();
    }

    /**
     * Ürün kategori cache'lerini temizle
     */
    public static function clearProductCategoryCache(?int $categoryId = null): void
    {
        $patterns = [
            'active_product_categories',
            'popular_product_categories',
            'category_products_paginated'
        ];
        
        if ($categoryId) {
            $patterns[] = 'product_category_' . $categoryId;
            $patterns[] = 'category_products_paginated_' . $categoryId;
        }

        foreach ($patterns as $pattern) {
            for ($i = 1; $i <= 100; $i++) {
                Cache::forget($pattern . '_' . $i);
                Cache::forget($pattern . $i);
            }
            Cache::forget($pattern);
        }

        // Ürün cache'lerini de temizle çünkü bağlantılı
        self::clearProductCache();
    }

    /**
     * Tüm cache'leri temizle
     */
    public static function clearAll(): void
    {
        // Bu basic implementation için cache'i komple temizliyoruz
        Cache::flush();
    }

    /**
     * Cache key oluştur
     */
    public static function generateKey(string $prefix, ...$params): string
    {
        return $prefix . '_' . implode('_', array_filter($params));
    }
}
