<?php

namespace App\Observers;

use App\Models\About;
use Illuminate\Support\Facades\Cache;

class AboutObserver
{
    public function created(About $about): void
    {
        $this->clearCache();
    }

    public function updated(About $about): void
    {
        $this->clearCache();
    }

    public function deleted(About $about): void
    {
        $this->clearCache();
    }

    public function restored(About $about): void
    {
        $this->clearCache();
    }

    public function forceDeleted(About $about): void
    {
        $this->clearCache();
    }

    private function clearCache(): void
    {
        $patterns = [
            'active_about_sections',
            'about_by_section',
            'main_about_content',
            'about_sections_by_category',
        ];

        foreach ($patterns as $pattern) {
            Cache::forget($pattern);
            for ($i = 1; $i <= 20; $i++) {
                Cache::forget($pattern.'_'.$i);
            }
        }
    }
}
