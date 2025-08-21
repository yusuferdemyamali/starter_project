<?php

namespace App\Models;

use App\Services\CacheService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    protected $fillable = [
        'title',
        'content',
        'image',
        'section',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Scope: Aktif hakkımda bölümleri
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
     * Cache'li aktif hakkımda bölümlerini getir
     */
    public static function getCachedActiveAboutSections()
    {
        $key = CacheService::generateKey('active_about_sections');
        
        return CacheService::remember(
            $key,
            CacheService::LONG_TTL, // Hakkımda bilgileri nadiren değişir
            function () {
                return static::active()
                    ->ordered()
                    ->get();
            }
        );
    }

    /**
     * Cache'li bölüm adına göre hakkımda içeriği getir
     */
    public static function getCachedAboutBySection(string $section)
    {
        $key = CacheService::generateKey('about_by_section', $section);
        
        return CacheService::remember(
            $key,
            CacheService::LONG_TTL,
            function () use ($section) {
                return static::active()
                    ->where('section', $section)
                    ->ordered()
                    ->first();
            }
        );
    }

    /**
     * Cache'li ana hakkımda içeriğini getir
     */
    public static function getCachedMainAboutContent()
    {
        $key = CacheService::generateKey('main_about_content');
        
        return CacheService::remember(
            $key,
            CacheService::LONG_TTL,
            function () {
                return static::active()
                    ->ordered()
                    ->first();
            }
        );
    }

    /**
     * Cache'li tüm hakkımda bölümlerini kategorize ederek getir
     */
    public static function getCachedAboutSectionsByCategory()
    {
        $key = CacheService::generateKey('about_sections_by_category');
        
        return CacheService::remember(
            $key,
            CacheService::LONG_TTL,
            function () {
                return static::active()
                    ->ordered()
                    ->get()
                    ->groupBy('section');
            }
        );
    }
}
