<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Blog;
use App\Models\Team;
use App\Models\Gallery;
use App\Models\Reference;
use App\Models\Faq;
use App\Models\About;

class PerformanceTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:performance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test cache performance across all models';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Performans Testi Başlatılıyor...');
        
        // Query sayacını sıfırla
        DB::flushQueryLog();
        DB::enableQueryLog();

        $models = [
            'Blog' => Blog::class,
            'Team' => Team::class,
            'Gallery' => Gallery::class,
            'Reference' => Reference::class,
            'Faq' => Faq::class,
            'About' => About::class,
        ];

        foreach ($models as $name => $modelClass) {
            $this->testModel($name, $modelClass);
        }
        
        $this->info('✅ Performans testi tamamlandı!');
    }

    private function testModel($name, $modelClass)
    {
        $this->info("\n📊 {$name} Model Performans Testi:");
        
        // Cache'siz test
        DB::flushQueryLog();
        $start = microtime(true);
        $modelClass::all();
        $withoutCacheTime = microtime(true) - $start;
        $withoutCacheQueries = count(DB::getQueryLog());
        
        // Cache'li test
        DB::flushQueryLog();
        $start = microtime(true);
        if (method_exists($modelClass, 'getCachedAll')) {
            $modelClass::getCachedAll();
        } else {
            $modelClass::all();
        }
        $withCacheTime = microtime(true) - $start;
        $withCacheQueries = count(DB::getQueryLog());
        
        // İkinci cache çağrısı (tamamen cache'den)
        DB::flushQueryLog();
        $start = microtime(true);
        if (method_exists($modelClass, 'getCachedAll')) {
            $modelClass::getCachedAll();
        } else {
            $modelClass::all();
        }
        $secondCacheTime = microtime(true) - $start;
        $secondCacheQueries = count(DB::getQueryLog());
        
        // Sonuçları göster
        $this->line("Cache'siz: " . number_format($withoutCacheTime * 1000, 2) . "ms ({$withoutCacheQueries} query)");
        $this->line("İlk Cache: " . number_format($withCacheTime * 1000, 2) . "ms ({$withCacheQueries} query)");
        $this->line("İkinci Cache: " . number_format($secondCacheTime * 1000, 2) . "ms ({$secondCacheQueries} query)");
        
        if ($withoutCacheTime > 0) {
            $improvement = (($withoutCacheTime - $secondCacheTime) / $withoutCacheTime) * 100;
            $queryReduction = (($withoutCacheQueries - $secondCacheQueries) / max($withoutCacheQueries, 1)) * 100;
            
            $this->line("🚀 Hız iyileştirmesi: " . number_format($improvement, 1) . "%");
            $this->line("📉 Query azalması: " . number_format($queryReduction, 1) . "%");
        }
    }
}
