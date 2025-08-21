<?php

namespace App\Console\Commands;

use App\Models\Blog;
use App\Models\Faq;
use App\Models\Reference;
use App\Models\Team;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DatabaseIndexPerformanceTest extends Command
{
    protected $signature = 'test:database-indexes';

    protected $description = 'Test database index performance improvements';

    public function handle()
    {
        $this->info('📊 Database Index Performans Testi Başlatılıyor...');

        DB::enableQueryLog();

        $this->testBlogQueries();
        $this->testComplexQueries();

        $this->info('✅ Database index performans testi tamamlandı!');
    }

    private function test_blog_queries()
    {
        $this->info("\n🔍 Blog Index Performansı:");

        // Test 1: Active blogs with published date
        DB::flushQueryLog();
        $start = microtime(true);

        $activeBlogs = Blog::where('is_active', true)
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->limit(10)
            ->get();

        $time1 = microtime(true) - $start;
        $queries1 = count(DB::getQueryLog());

        $this->line('Aktif + Yayınlanan Bloglar: '.number_format($time1 * 1000, 2)."ms ({$queries1} query) - {$activeBlogs->count()} sonuç");

        // Test 2: Blogs with category relationship
        DB::flushQueryLog();
        $start = microtime(true);

        $blogsWithCategory = Blog::with('category')
            ->where('is_active', true)
            ->limit(5)
            ->get();

        $time2 = microtime(true) - $start;
        $queries2 = count(DB::getQueryLog());

        $this->line('Kategori ile Bloglar: '.number_format($time2 * 1000, 2)."ms ({$queries2} query) - {$blogsWithCategory->count()} sonuç");

        // Test 3: Popular blogs (views index)
        DB::flushQueryLog();
        $start = microtime(true);

        $popularBlogs = Blog::where('is_active', true)
            ->orderBy('views', 'desc')
            ->limit(5)
            ->get();

        $time3 = microtime(true) - $start;
        $queries3 = count(DB::getQueryLog());

        $this->line('Popüler Bloglar (Views): '.number_format($time3 * 1000, 2)."ms ({$queries3} query) - {$popularBlogs->count()} sonuç");
    }

    private function test_complex_queries()
    {
        $this->info("\n🔍 Karmaşık Query Performansı:");

        // Test 1: Team members ordered
        DB::flushQueryLog();
        $start = microtime(true);

        $activeTeam = Team::where('is_active', true)
            ->orderBy('order')
            ->get();

        $time1 = microtime(true) - $start;
        $queries1 = count(DB::getQueryLog());

        $this->line('Aktif Takım Üyeleri: '.number_format($time1 * 1000, 2)."ms ({$queries1} query) - {$activeTeam->count()} sonuç");

        // Test 2: References by company
        DB::flushQueryLog();
        $start = microtime(true);

        $references = Reference::where('is_active', true)
            ->where('company', 'like', '%company%')
            ->orderBy('order')
            ->get();

        $time2 = microtime(true) - $start;
        $queries2 = count(DB::getQueryLog());

        $this->line('Şirket Referansları: '.number_format($time2 * 1000, 2)."ms ({$queries2} query) - {$references->count()} sonuç");

        // Test 3: FAQ search
        DB::flushQueryLog();
        $start = microtime(true);

        $faqs = Faq::where('is_active', true)
            ->where('question', 'like', '%test%')
            ->orderBy('order')
            ->get();

        $time3 = microtime(true) - $start;
        $queries3 = count(DB::getQueryLog());

        $this->line('SSS Arama: '.number_format($time3 * 1000, 2)."ms ({$queries3} query) - {$faqs->count()} sonuç");
    }
}
