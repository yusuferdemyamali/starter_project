<?php

namespace App\Observers;

use App\Models\Gallery;
use App\Services\CacheService;

class GalleryObserver
{
    public function created(Gallery $gallery): void
    {
        $this->clearCache();
    }

    public function updated(Gallery $gallery): void
    {
        $this->clearCache();
    }

    public function deleted(Gallery $gallery): void
    {
        $this->clearCache();
    }

    public function restored(Gallery $gallery): void
    {
        $this->clearCache();
    }

    public function forceDeleted(Gallery $gallery): void
    {
        $this->clearCache();
    }

    private function clearCache(): void
    {
        $patterns = [
            'active_gallery',
            'gallery_by_slug',
            'latest_gallery'
        ];

        foreach ($patterns as $pattern) {
            for ($i = 1; $i <= 50; $i++) {
                CacheService::remember($pattern . '_' . $i, 1, fn() => null);
            }
        }
    }
}
