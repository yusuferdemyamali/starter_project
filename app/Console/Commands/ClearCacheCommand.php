<?php

namespace App\Console\Commands;

use App\Services\CacheService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:clear-app 
                            {--type=all : Cache type to clear (all, blogs, products, categories)}
                            {--force : Force clear without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear application specific caches (blogs, products, categories)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $type = $this->option('type');
        $force = $this->option('force');

        if (! $force && ! $this->confirm("Are you sure you want to clear {$type} cache?")) {
            $this->info('Operation cancelled.');

            return self::SUCCESS;
        }

        $this->info('Clearing cache...');

        try {
            match ($type) {
                'blogs' => $this->clearBlogsCache(),
                'products' => $this->clearProductsCache(),
                'categories' => $this->clearCategoriesCache(),
                'all' => $this->clearAllCache(),
                default => $this->clearAllCache(),
            };

            $this->info("✅ {$type} cache cleared successfully!");

            // Cache statistics
            $this->showCacheStats();

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error("❌ Error clearing cache: {$e->getMessage()}");

            return self::FAILURE;
        }
    }

    /**
     * Clear blog caches
     */
    private function clearBlogsCache(): void
    {
        CacheService::clearBlogCache();
        CacheService::clearBlogCategoryCache();
        $this->line('• Blog caches cleared');
    }

    /**
     * Clear product caches
     */
    private function clearProductsCache(): void
    {
        CacheService::clearProductCache();
        CacheService::clearProductCategoryCache();
        $this->line('• Product caches cleared');
    }

    /**
     * Clear category caches
     */
    private function clearCategoriesCache(): void
    {
        CacheService::clearBlogCategoryCache();
        CacheService::clearProductCategoryCache();
        $this->line('• Category caches cleared');
    }

    /**
     * Clear all application caches
     */
    private function clearAllCache(): void
    {
        CacheService::clearAll();
        $this->line('• All application caches cleared');
    }

    /**
     * Show cache statistics
     */
    private function showCacheStats(): void
    {
        $this->newLine();
        $this->info('📊 Cache Statistics:');

        // Test cache operations
        $testKey = 'cache_test_'.time();
        $startTime = microtime(true);

        Cache::put($testKey, 'test', 1);
        $cached = Cache::get($testKey);
        Cache::forget($testKey);

        $responseTime = round((microtime(true) - $startTime) * 1000, 2);

        $this->table(
            ['Metric', 'Value'],
            [
                ['Cache Driver', config('cache.default')],
                ['Response Time', $responseTime.' ms'],
                ['Status', $cached === 'test' ? '✅ Working' : '❌ Not Working'],
            ]
        );
    }
}
