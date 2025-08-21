<?php

namespace App\Observers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

class SiteSettingObserver
{
    public function created(SiteSetting $siteSetting): void
    {
        $this->clearCache();
    }

    public function updated(SiteSetting $siteSetting): void
    {
        $this->clearCache();
    }

    public function deleted(SiteSetting $siteSetting): void
    {
        $this->clearCache();
    }

    public function restored(SiteSetting $siteSetting): void
    {
        $this->clearCache();
    }

    public function forceDeleted(SiteSetting $siteSetting): void
    {
        $this->clearCache();
    }

    private function clearCache(): void
    {
        $patterns = [
            'site_settings',
            'site_setting',
            'social_media_links',
            'contact_info',
            'seo_info',
            'maintenance_status',
        ];

        foreach ($patterns as $pattern) {
            Cache::forget($pattern);
            for ($i = 1; $i <= 20; $i++) {
                Cache::forget($pattern.'_'.$i);
            }
        }
    }
}
