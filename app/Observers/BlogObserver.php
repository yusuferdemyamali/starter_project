<?php

namespace App\Observers;

use App\Models\Blog;
use App\Services\CacheService;

class BlogObserver
{
    /**
     * Handle the Blog "created" event.
     */
    public function created(Blog $blog): void
    {
        $this->clearCache($blog);
    }

    /**
     * Handle the Blog "updated" event.
     */
    public function updated(Blog $blog): void
    {
        $this->clearCache($blog);

        // Kategori değiştirildi mi kontrol et
        if ($blog->isDirty('blog_category_id')) {
            $originalCategoryId = $blog->getOriginal('blog_category_id');
            if ($originalCategoryId) {
                CacheService::clearBlogCache(null, $originalCategoryId);
            }
        }
    }

    /**
     * Handle the Blog "deleted" event.
     */
    public function deleted(Blog $blog): void
    {
        $this->clearCache($blog);
    }

    /**
     * Handle the Blog "restored" event.
     */
    public function restored(Blog $blog): void
    {
        $this->clearCache($blog);
    }

    /**
     * Handle the Blog "force deleted" event.
     */
    public function forceDeleted(Blog $blog): void
    {
        $this->clearCache($blog);
    }

    /**
     * Cache'leri temizle
     */
    private function clearCache(Blog $blog): void
    {
        CacheService::clearBlogCache($blog->id, $blog->blog_category_id);
    }
}
