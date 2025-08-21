<?php

namespace App\Models;

use App\Services\CacheService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    protected $fillable = [
        'client_name',
        'company',
        'testimonial',
        'photo',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Scope: Aktif referanslar
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
        return $query->orderBy('order', 'asc')->orderBy('client_name', 'asc');
    }

    /**
     * Cache'li aktif referansları getir
     */
    public static function getCachedActiveReferences(int $limit = 10)
    {
        $key = CacheService::generateKey('active_references', $limit);
        
        return CacheService::remember(
            $key,
            CacheService::LONG_TTL,
            function () use ($limit) {
                return static::active()
                    ->ordered()
                    ->limit($limit)
                    ->get();
            }
        );
    }

    /**
     * Cache'li şirket adına göre referanslar
     */
    public static function getCachedReferencesByCompany(string $company)
    {
        $key = CacheService::generateKey('references_by_company', $company);
        
        return CacheService::remember(
            $key,
            CacheService::DEFAULT_TTL,
            function () use ($company) {
                return static::active()
                    ->where('company', 'like', '%' . $company . '%')
                    ->ordered()
                    ->get();
            }
        );
    }

    /**
     * Cache'li öne çıkan referanslar (rastgele sıra)
     */
    public static function getCachedFeaturedReferences(int $limit = 6)
    {
        $key = CacheService::generateKey('featured_references', $limit);
        
        return CacheService::remember(
            $key,
            CacheService::DEFAULT_TTL,
            function () use ($limit) {
                return static::active()
                    ->inRandomOrder()
                    ->limit($limit)
                    ->get();
            }
        );
    }

    /**
     * Cache'li tek referans detayı
     */
    public static function getCachedReference(int $id)
    {
        $key = CacheService::generateKey('reference', $id);
        
        return CacheService::remember(
            $key,
            CacheService::LONG_TTL,
            function () use ($id) {
                return static::active()->find($id);
            }
        );
    }
}
