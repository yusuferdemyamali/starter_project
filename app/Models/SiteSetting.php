<?php

namespace App\Models;

use App\Services\CacheService;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name',
        'site_email',
        'site_phone',
        'site_address',
        'site_logo',
        'site_favicon',
        'site_facebook_url',
        'site_twitter_url',
        'site_linkedin_url',
        'site_instagram_url',
        'site_youtube_url',
        'site_working_hours',
        'site_maps_embed',
        'site_seo_title',
        'site_seo_description',
        'site_seo_keywords',
        'is_maintenance',
    ];

    protected $casts = [
        'is_maintenance' => 'boolean',
    ];

    /**
     * Cache'li site ayarlarını getir
     */
    public static function getCachedSettings()
    {
        $key = CacheService::generateKey('site_settings');
        
        return CacheService::remember(
            $key,
            CacheService::LONG_TTL, // Site ayarları çok nadiren değişir
            function () {
                return static::first() ?? new static();
            }
        );
    }

    /**
     * Cache'li belirli bir ayarı getir
     */
    public static function getCachedSetting(string $key, $default = null)
    {
        $cacheKey = CacheService::generateKey('site_setting', $key);
        
        return CacheService::remember(
            $cacheKey,
            CacheService::LONG_TTL,
            function () use ($key, $default) {
                $settings = static::first();
                return $settings ? ($settings->{$key} ?? $default) : $default;
            }
        );
    }

    /**
     * Cache'li sosyal medya linklerini getir
     */
    public static function getCachedSocialMediaLinks()
    {
        $key = CacheService::generateKey('social_media_links');
        
        return CacheService::remember(
            $key,
            CacheService::LONG_TTL,
            function () {
                $settings = static::first();
                if (!$settings) return [];

                return [
                    'facebook' => $settings->site_facebook_url,
                    'twitter' => $settings->site_twitter_url,
                    'linkedin' => $settings->site_linkedin_url,
                    'instagram' => $settings->site_instagram_url,
                    'youtube' => $settings->site_youtube_url,
                ];
            }
        );
    }

    /**
     * Cache'li iletişim bilgilerini getir
     */
    public static function getCachedContactInfo()
    {
        $key = CacheService::generateKey('contact_info');
        
        return CacheService::remember(
            $key,
            CacheService::LONG_TTL,
            function () {
                $settings = static::first();
                if (!$settings) return [];

                return [
                    'email' => $settings->site_email,
                    'phone' => $settings->site_phone,
                    'address' => $settings->site_address,
                    'working_hours' => $settings->site_working_hours,
                    'maps_embed' => $settings->site_maps_embed,
                ];
            }
        );
    }

    /**
     * Cache'li SEO bilgilerini getir
     */
    public static function getCachedSeoInfo()
    {
        $key = CacheService::generateKey('seo_info');
        
        return CacheService::remember(
            $key,
            CacheService::LONG_TTL,
            function () {
                $settings = static::first();
                if (!$settings) return [];

                return [
                    'title' => $settings->site_seo_title,
                    'description' => $settings->site_seo_description,
                    'keywords' => $settings->site_seo_keywords,
                ];
            }
        );
    }

    /**
     * Cache'li bakım modu durumunu kontrol et
     */
    public static function getCachedMaintenanceStatus(): bool
    {
        $key = CacheService::generateKey('maintenance_status');
        
        return CacheService::remember(
            $key,
            CacheService::SHORT_TTL, // Bakım modu sık kontrol edilebilir
            function () {
                $settings = static::first();
                return $settings ? (bool) $settings->is_maintenance : false;
            }
        );
    }
}
