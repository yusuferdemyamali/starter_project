<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\CacheService;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        $this->clearCache($product);
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        $this->clearCache($product);
        
        // Kategori değiştirildi mi kontrol et
        if ($product->isDirty('category_id')) {
            $originalCategoryId = $product->getOriginal('category_id');
            if ($originalCategoryId) {
                CacheService::clearProductCache(null, $originalCategoryId);
            }
        }
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        $this->clearCache($product);
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        $this->clearCache($product);
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        $this->clearCache($product);
    }

    /**
     * Cache'leri temizle
     */
    private function clearCache(Product $product): void
    {
        CacheService::clearProductCache($product->id, $product->category_id);
    }
}
