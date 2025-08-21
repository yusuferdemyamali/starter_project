<?php

namespace App\Filament\Widgets;

use App\Models\Blog;
use App\Models\Product;
use App\Models\Reference;
use App\Models\Team;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsOverview extends BaseWidget
{
    protected static ?int $sort = 0;

    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        $totalUsers = User::count();
        $totalBlogs = Blog::count();
        $activeBlogsCount = Blog::where('is_active', true)->count();
        $totalProducts = Product::count();
        $activeProductsCount = Product::where('is_active', true)->count();
        $totalReferences = Reference::count();
        $totalTeam = Team::count();

        // Bu ay eklenen blog sayısı
        $blogsThisMonth = Blog::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Bu ay eklenen ürün sayısı
        $productsThisMonth = Product::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return [
            Stat::make('Toplam İçerik Sayısı', $totalBlogs + $totalProducts + $totalReferences)
                ->description('Blog, Ürün ve Referanslar')
                ->descriptionIcon('heroicon-m-document-duplicate')
                ->color('primary')
                ->chart([7, 12, 18, 22, 15, 28, $totalBlogs + $totalProducts + $totalReferences]),

            Stat::make('Aktif Blog Yazıları', $activeBlogsCount)
                ->description("Toplam {$totalBlogs} yazıdan")
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('success')
                ->url(route('filament.admin.resources.blogs.index'))
                ->chart([3, 7, $activeBlogsCount]),

            Stat::make('Ürün Kataloğu', $activeProductsCount)
                ->description("Toplam {$totalProducts} üründen")
                ->descriptionIcon('heroicon-m-cube')
                ->color('warning')
                ->url(route('filament.admin.resources.products.index'))
                ->chart([1, 3, $activeProductsCount]),

            Stat::make('Ekip Üyeleri', $totalTeam)
                ->description('Aktif ekip üyesi')
                ->descriptionIcon('heroicon-m-users')
                ->color('info')
                ->url(route('filament.admin.resources.teams.index')),

            Stat::make('Müşteri Referansları', $totalReferences)
                ->description('Onaylanmış referans')
                ->descriptionIcon('heroicon-m-star')
                ->color('gray')
                ->url(route('filament.admin.resources.references.index')),

            Stat::make('Bu Ayki Yeni İçerik', $blogsThisMonth + $productsThisMonth)
                ->description("{$blogsThisMonth} blog, {$productsThisMonth} ürün")
                ->descriptionIcon('heroicon-m-plus-circle')
                ->color($blogsThisMonth + $productsThisMonth > 0 ? 'success' : 'danger')
                ->chart([2, 4, 6, $blogsThisMonth + $productsThisMonth]),
        ];
    }
}
