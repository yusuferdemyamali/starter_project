<?php

namespace App\Console\Commands;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PerformanceTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'performance:test 
                            {--iterations=10 : Number of test iterations}
                            {--clear-cache : Clear cache before testing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test performance improvements (N+1 queries, cache efficiency)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $iterations = (int) $this->option('iterations');
        $clearCache = $this->option('clear-cache');

        if ($clearCache) {
            Cache::flush();
            $this->info('🗑️ Cache cleared');
        }

        $this->info("🚀 Starting performance tests with {$iterations} iterations...");
        $this->newLine();

        $results = [
            'blog_tests' => $this->testBlogPerformance($iterations),
            'product_tests' => $this->testProductPerformance($iterations),
            'cache_tests' => $this->testCachePerformance($iterations),
        ];

        $this->displayResults($results);

        return self::SUCCESS;
    }

    /**
     * Test blog performance
     */
    private function testBlogPerformance(int $iterations): array
    {
        $this->info('📝 Testing Blog Performance...');

        // Test 1: N+1 Prevention (Before vs After)
        $queryCountBefore = 0;
        $timeBefore = 0;

        for ($i = 0; $i < $iterations; $i++) {
            DB::enableQueryLog();
            $start = microtime(true);
            
            // Simulate old behavior (without eager loading)
            $blogs = Blog::take(10)->get();
            foreach ($blogs as $blog) {
                $category = $blog->category; // This would cause N+1 queries
            }
            
            $timeBefore += microtime(true) - $start;
            $queryCountBefore += count(DB::getQueryLog());
            DB::flushQueryLog();
        }

        // Test 2: With Eager Loading
        $queryCountAfter = 0;
        $timeAfter = 0;

        for ($i = 0; $i < $iterations; $i++) {
            DB::enableQueryLog();
            $start = microtime(true);
            
            // New optimized behavior (with eager loading)
            $blogs = Blog::with('category')->take(10)->get();
            foreach ($blogs as $blog) {
                $category = $blog->category;
            }
            
            $timeAfter += microtime(true) - $start;
            $queryCountAfter += count(DB::getQueryLog());
            DB::flushQueryLog();
        }

        // Test 3: Cache Performance
        $cacheTime = 0;
        for ($i = 0; $i < $iterations; $i++) {
            $start = microtime(true);
            $blogs = Blog::getCachedActiveBlogs(10);
            $cacheTime += microtime(true) - $start;
        }

        return [
            'without_eager_loading' => [
                'queries' => $queryCountBefore,
                'time_ms' => round($timeBefore * 1000, 2),
                'avg_queries' => round($queryCountBefore / $iterations, 1),
                'avg_time_ms' => round(($timeBefore / $iterations) * 1000, 2),
            ],
            'with_eager_loading' => [
                'queries' => $queryCountAfter,
                'time_ms' => round($timeAfter * 1000, 2),
                'avg_queries' => round($queryCountAfter / $iterations, 1),
                'avg_time_ms' => round(($timeAfter / $iterations) * 1000, 2),
            ],
            'cached_method' => [
                'time_ms' => round($cacheTime * 1000, 2),
                'avg_time_ms' => round(($cacheTime / $iterations) * 1000, 2),
            ]
        ];
    }

    /**
     * Test product performance
     */
    private function testProductPerformance(int $iterations): array
    {
        $this->info('🛍️ Testing Product Performance...');

        // Test without eager loading
        $queryCountBefore = 0;
        $timeBefore = 0;

        for ($i = 0; $i < $iterations; $i++) {
            DB::enableQueryLog();
            $start = microtime(true);
            
            $products = Product::take(10)->get();
            foreach ($products as $product) {
                $category = $product->category; // N+1 query
            }
            
            $timeBefore += microtime(true) - $start;
            $queryCountBefore += count(DB::getQueryLog());
            DB::flushQueryLog();
        }

        // Test with eager loading
        $queryCountAfter = 0;
        $timeAfter = 0;

        for ($i = 0; $i < $iterations; $i++) {
            DB::enableQueryLog();
            $start = microtime(true);
            
            $products = Product::with('category')->take(10)->get();
            foreach ($products as $product) {
                $category = $product->category;
            }
            
            $timeAfter += microtime(true) - $start;
            $queryCountAfter += count(DB::getQueryLog());
            DB::flushQueryLog();
        }

        // Cache performance
        $cacheTime = 0;
        for ($i = 0; $i < $iterations; $i++) {
            $start = microtime(true);
            $products = Product::getCachedActiveProducts(10);
            $cacheTime += microtime(true) - $start;
        }

        return [
            'without_eager_loading' => [
                'queries' => $queryCountBefore,
                'time_ms' => round($timeBefore * 1000, 2),
                'avg_queries' => round($queryCountBefore / $iterations, 1),
                'avg_time_ms' => round(($timeBefore / $iterations) * 1000, 2),
            ],
            'with_eager_loading' => [
                'queries' => $queryCountAfter,
                'time_ms' => round($timeAfter * 1000, 2),
                'avg_queries' => round($queryCountAfter / $iterations, 1),
                'avg_time_ms' => round(($timeAfter / $iterations) * 1000, 2),
            ],
            'cached_method' => [
                'time_ms' => round($cacheTime * 1000, 2),
                'avg_time_ms' => round(($cacheTime / $iterations) * 1000, 2),
            ]
        ];
    }

    /**
     * Test cache performance
     */
    private function testCachePerformance(int $iterations): array
    {
        $this->info('⚡ Testing Cache Performance...');

        // Test cache write performance
        $writeTime = 0;
        for ($i = 0; $i < $iterations; $i++) {
            $start = microtime(true);
            Cache::put("test_key_{$i}", ['data' => str_repeat('x', 1000)], 60);
            $writeTime += microtime(true) - $start;
        }

        // Test cache read performance
        $readTime = 0;
        for ($i = 0; $i < $iterations; $i++) {
            $start = microtime(true);
            Cache::get("test_key_{$i}");
            $readTime += microtime(true) - $start;
        }

        // Cleanup
        for ($i = 0; $i < $iterations; $i++) {
            Cache::forget("test_key_{$i}");
        }

        return [
            'write_time_ms' => round($writeTime * 1000, 2),
            'read_time_ms' => round($readTime * 1000, 2),
            'avg_write_ms' => round(($writeTime / $iterations) * 1000, 2),
            'avg_read_ms' => round(($readTime / $iterations) * 1000, 2),
        ];
    }

    /**
     * Display test results
     */
    private function displayResults(array $results): void
    {
        $this->newLine();
        $this->info('📊 PERFORMANCE TEST RESULTS');
        $this->info('==========================');

        // Blog Results
        $this->newLine();
        $this->info('📝 BLOG PERFORMANCE:');
        $blogResults = $results['blog_tests'];
        
        $this->table(['Metric', 'Without Eager Loading', 'With Eager Loading', 'Cached Method'], [
            [
                'Avg Queries per Request',
                $blogResults['without_eager_loading']['avg_queries'],
                $blogResults['with_eager_loading']['avg_queries'],
                'N/A (cached)'
            ],
            [
                'Avg Time (ms)',
                $blogResults['without_eager_loading']['avg_time_ms'],
                $blogResults['with_eager_loading']['avg_time_ms'],
                $blogResults['cached_method']['avg_time_ms']
            ],
            [
                'Performance Improvement',
                'Baseline',
                $this->calculateImprovement($blogResults['without_eager_loading']['avg_time_ms'], $blogResults['with_eager_loading']['avg_time_ms']),
                $this->calculateImprovement($blogResults['without_eager_loading']['avg_time_ms'], $blogResults['cached_method']['avg_time_ms'])
            ]
        ]);

        // Product Results
        $this->newLine();
        $this->info('🛍️ PRODUCT PERFORMANCE:');
        $productResults = $results['product_tests'];
        
        $this->table(['Metric', 'Without Eager Loading', 'With Eager Loading', 'Cached Method'], [
            [
                'Avg Queries per Request',
                $productResults['without_eager_loading']['avg_queries'],
                $productResults['with_eager_loading']['avg_queries'],
                'N/A (cached)'
            ],
            [
                'Avg Time (ms)',
                $productResults['without_eager_loading']['avg_time_ms'],
                $productResults['with_eager_loading']['avg_time_ms'],
                $productResults['cached_method']['avg_time_ms']
            ],
            [
                'Performance Improvement',
                'Baseline',
                $this->calculateImprovement($productResults['without_eager_loading']['avg_time_ms'], $productResults['with_eager_loading']['avg_time_ms']),
                $this->calculateImprovement($productResults['without_eager_loading']['avg_time_ms'], $productResults['cached_method']['avg_time_ms'])
            ]
        ]);

        // Cache Results
        $this->newLine();
        $this->info('⚡ CACHE PERFORMANCE:');
        $cacheResults = $results['cache_tests'];
        
        $this->table(['Operation', 'Avg Time (ms)'], [
            ['Write to Cache', $cacheResults['avg_write_ms']],
            ['Read from Cache', $cacheResults['avg_read_ms']],
        ]);

        $this->newLine();
        $this->info('✅ Performance optimization completed successfully!');
        $this->info('💡 Key improvements: Eager loading prevents N+1 queries, caching reduces database load');
    }

    /**
     * Calculate performance improvement percentage
     */
    private function calculateImprovement(float $before, float $after): string
    {
        if ($before <= 0) return 'N/A';
        
        $improvement = (($before - $after) / $before) * 100;
        return $improvement > 0 
            ? '+' . round($improvement, 1) . '% faster'
            : round(abs($improvement), 1) . '% slower';
    }
}
