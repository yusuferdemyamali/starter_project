<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Foreign key check'lerini geçici olarak kapat
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->command->info('Creating test data...');

        // Blog kategorileri temizle ve oluştur
        BlogCategory::truncate();
        $this->command->info('Creating blog categories...');

        for ($i = 1; $i <= 5; $i++) {
            BlogCategory::create([
                'name' => 'Kategori '.$i,
                'slug' => 'kategori-'.$i,
                'is_active' => true,
            ]);
        }
        $this->command->info('✅ 5 blog categories created');

        // Blogları temizle ve oluştur
        Blog::truncate();
        $this->command->info('Creating blogs...');

        for ($i = 1; $i <= 50; $i++) {
            Blog::create([
                'title' => 'Blog Yazısı '.$i,
                'slug' => 'blog-yazisi-'.$i,
                'content' => 'Bu '.$i.'. blog yazısının içeriği. Lorem ipsum dolor sit amet consectetur adipisicing elit.',
                'excerpt' => 'Bu '.$i.'. blog yazısının kısa özeti',
                'author' => 'Test Author',
                'blog_category_id' => rand(1, 5),
                'is_active' => true,
                'published_at' => now()->subDays(rand(1, 30)),
            ]);
        }
        $this->command->info('✅ 50 blogs created');

        // Ürün kategorilerini temizle ve oluştur
        ProductCategory::truncate();
        $this->command->info('Creating product categories...');

        for ($i = 1; $i <= 8; $i++) {
            ProductCategory::create([
                'name' => 'Ürün Kategorisi '.$i,
                'description' => 'Bu '.$i.'. kategori açıklaması ve detayları',
                'is_active' => true,
                'sort_order' => $i,
                'color' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)), // Random color
            ]);
        }
        $this->command->info('✅ 8 product categories created');

        // Ürünleri temizle ve oluştur
        Product::truncate();
        $this->command->info('Creating products...');

        for ($i = 1; $i <= 100; $i++) {
            Product::create([
                'name' => 'Ürün '.$i,
                'description' => 'Bu '.$i.'. ürünün detaylı açıklaması ve özellikleri. Kaliteli malzemeden üretilmiştir.',
                'price' => rand(10, 1000) + (rand(0, 99) / 100), // Random price with decimals
                'category_id' => rand(1, 8),
                'is_active' => rand(0, 10) > 1, // 90% aktif
            ]);
        }
        $this->command->info('✅ 100 products created');

        // Foreign key check'lerini tekrar aç
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('🎉 Test data seeding completed successfully!');
        $this->command->info('📊 Summary:');
        $this->command->table(
            ['Model', 'Count'],
            [
                ['Blog Categories', BlogCategory::count()],
                ['Blogs', Blog::count()],
                ['Product Categories', ProductCategory::count()],
                ['Products', Product::count()],
            ]
        );
    }
}
