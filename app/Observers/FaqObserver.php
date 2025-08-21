<?php

namespace App\Observers;

use App\Models\Faq;
use Illuminate\Support\Facades\Cache;

class FaqObserver
{
    public function created(Faq $faq): void
    {
        $this->clearCache();
    }

    public function updated(Faq $faq): void
    {
        $this->clearCache();
    }

    public function deleted(Faq $faq): void
    {
        $this->clearCache();
    }

    public function restored(Faq $faq): void
    {
        $this->clearCache();
    }

    public function forceDeleted(Faq $faq): void
    {
        $this->clearCache();
    }

    private function clearCache(): void
    {
        $patterns = [
            'active_faqs',
            'faqs_by_category',
            'popular_faqs',
            'faq_categories',
        ];

        foreach ($patterns as $pattern) {
            Cache::forget($pattern);
            for ($i = 1; $i <= 50; $i++) {
                Cache::forget($pattern.'_'.$i);
            }
        }
    }
}
