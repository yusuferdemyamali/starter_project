<?php

namespace App\Models;

use App\Services\CacheService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'answer',
        'category',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Scope: Aktif FAQ'lar
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
        return $query->orderBy('order', 'asc')->orderBy('created_at', 'asc');
    }

    /**
     * Cache'li aktif FAQ'ları getir
     */
    public static function getCachedActiveFaqs()
    {
        $key = CacheService::generateKey('active_faqs');
        
        return CacheService::remember(
            $key,
            CacheService::LONG_TTL,
            function () {
                return static::active()
                    ->ordered()
                    ->get();
            }
        );
    }

    /**
     * Cache'li kategoriye göre FAQ'ları getir
     */
    public static function getCachedFaqsByCategory(string $category)
    {
        $key = CacheService::generateKey('faqs_by_category', $category);
        
        return CacheService::remember(
            $key,
            CacheService::DEFAULT_TTL,
            function () use ($category) {
                return static::active()
                    ->where('category', $category)
                    ->ordered()
                    ->get();
            }
        );
    }

    /**
     * Cache'li popüler FAQ'ları getir
     */
    public static function getCachedPopularFaqs(int $limit = 8)
    {
        $key = CacheService::generateKey('popular_faqs', $limit);
        
        return CacheService::remember(
            $key,
            CacheService::DEFAULT_TTL,
            function () use ($limit) {
                return static::active()
                    ->ordered()
                    ->limit($limit)
                    ->get();
            }
        );
    }

    /**
     * Cache'li FAQ kategorilerini getir
     */
    public static function getCachedFaqCategories()
    {
        $key = CacheService::generateKey('faq_categories');
        
        return CacheService::remember(
            $key,
            CacheService::LONG_TTL,
            function () {
                return static::active()
                    ->whereNotNull('category')
                    ->distinct()
                    ->pluck('category')
                    ->sort();
            }
        );
    }
}
