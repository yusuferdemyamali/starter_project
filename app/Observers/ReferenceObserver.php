<?php

namespace App\Observers;

use App\Models\Reference;
use Illuminate\Support\Facades\Cache;

class ReferenceObserver
{
    public function created(Reference $reference): void
    {
        $this->clearCache();
    }

    public function updated(Reference $reference): void
    {
        $this->clearCache();
    }

    public function deleted(Reference $reference): void
    {
        $this->clearCache();
    }

    public function restored(Reference $reference): void
    {
        $this->clearCache();
    }

    public function forceDeleted(Reference $reference): void
    {
        $this->clearCache();
    }

    private function clearCache(): void
    {
        $patterns = [
            'active_references',
            'references_by_company',
            'featured_references',
            'reference',
        ];

        foreach ($patterns as $pattern) {
            Cache::forget($pattern);
            for ($i = 1; $i <= 50; $i++) {
                Cache::forget($pattern.'_'.$i);
            }
        }
    }
}
