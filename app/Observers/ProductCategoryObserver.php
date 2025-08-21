<?php

namespace App\Observers;

use App\Models\ProductCategory;
use App\Services\CacheService;

class ProductCategoryObserver
{
    /**
     * Handle the ProductCategory "created" event.
     */
    public function created(ProductCategory $productCategory): void
    {
        $this->clearCache($productCategory);
    }

    /**
     * Handle the ProductCategory "updated" event.
     */
    public function updated(ProductCategory $productCategory): void
    {
        $this->clearCache($productCategory);
    }

    /**
     * Handle the ProductCategory "deleted" event.
     */
    public function deleted(ProductCategory $productCategory): void
    {
        $this->clearCache($productCategory);
    }

    /**
     * Handle the ProductCategory "restored" event.
     */
    public function restored(ProductCategory $productCategory): void
    {
        $this->clearCache($productCategory);
    }

    /**
     * Handle the ProductCategory "force deleted" event.
     */
    public function forceDeleted(ProductCategory $productCategory): void
    {
        $this->clearCache($productCategory);
    }

    /**
     * Cache'leri temizle
     */
    private function clearCache(ProductCategory $productCategory): void
    {
        CacheService::clearProductCategoryCache($productCategory->id);
    }
}
