<?php

namespace App\Console\Commands;

use App\Models\Blog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FilamentPerformanceTest extends Command
{
    protected $signature = 'test:filament-performance';

    protected $description = 'Test Filament N+1 query performance';

    public function handle()
    {
        $this->info('🔍 Filament N+1 Query Testi Başlatılıyor...');

        // Query log'u aktif et
        DB::enableQueryLog();

        // Blog'lar ve kategorileri için test
        $this->testBlogQueries();

        $this->info('✅ Filament N+1 testi tamamlandı!');
    }

    private function test_blog_queries()
    {
        $this->info("\n📊 Blog N+1 Query Testi:");

        // N+1 sorunu ile (eager loading olmadan)
        DB::flushQueryLog();
        $start = microtime(true);

        $blogs = Blog::all(); // 1 query
        foreach ($blogs as $blog) {
            // Her blog için ayrı query - bu N+1 sorununa neden olur
            $categoryName = $blog->category?->name ?? 'No Category'; // N query
        }

        $withoutEagerTime = microtime(true) - $start;
        $withoutEagerQueries = count(DB::getQueryLog());

        // Eager loading ile
        DB::flushQueryLog();
        $start = microtime(true);

        $blogs = Blog::with('category')->get(); // 1+1 query (blog + categories)
        foreach ($blogs as $blog) {
            // Kategori zaten yüklenmiş, ek query yok
            $categoryName = $blog->category?->name ?? 'No Category';
        }

        $withEagerTime = microtime(true) - $start;
        $withEagerQueries = count(DB::getQueryLog());

        // Sonuçları göster
        $blogCount = $blogs->count();
        $this->line('Eager Loading Olmadan: '.number_format($withoutEagerTime * 1000, 2)."ms ({$withoutEagerQueries} query)");
        $this->line('Eager Loading İle: '.number_format($withEagerTime * 1000, 2)."ms ({$withEagerQueries} query)");

        if ($withoutEagerTime > 0 && $withoutEagerQueries > $withEagerQueries) {
            $improvement = (($withoutEagerTime - $withEagerTime) / $withoutEagerTime) * 100;
            $queryReduction = (($withoutEagerQueries - $withEagerQueries) / max($withoutEagerQueries, 1)) * 100;

            $this->line('🚀 Hız iyileştirmesi: '.number_format($improvement, 1).'%');
            $this->line('📉 Query azalması: '.number_format($queryReduction, 1).'%');
        }

        $this->line("📝 Test edilen blog sayısı: {$blogCount}");
        $this->line('� Beklenen query sayısı - Eager Loading Olmadan: '.(1 + $blogCount)." (1 blog query + {$blogCount} kategori query)");
        $this->line('💡 Beklenen query sayısı - Eager Loading İle: 2 (1 blog query + 1 kategori query)');
    }
}
