<?php

namespace App\Observers;

use App\Models\BlogCategory;
use App\Services\CacheService;

class BlogCategoryObserver
{
    /**
     * Handle the BlogCategory "created" event.
     */
    public function created(BlogCategory $blogCategory): void
    {
        $this->clearCache($blogCategory);
    }

    /**
     * Handle the BlogCategory "updated" event.
     */
    public function updated(BlogCategory $blogCategory): void
    {
        $this->clearCache($blogCategory);
    }

    /**
     * Handle the BlogCategory "deleted" event.
     */
    public function deleted(BlogCategory $blogCategory): void
    {
        $this->clearCache($blogCategory);
    }

    /**
     * Handle the BlogCategory "restored" event.
     */
    public function restored(BlogCategory $blogCategory): void
    {
        $this->clearCache($blogCategory);
    }

    /**
     * Handle the BlogCategory "force deleted" event.
     */
    public function forceDeleted(BlogCategory $blogCategory): void
    {
        $this->clearCache($blogCategory);
    }

    /**
     * Cache'leri temizle
     */
    private function clearCache(BlogCategory $blogCategory): void
    {
        CacheService::clearBlogCategoryCache($blogCategory->id);
    }
}
