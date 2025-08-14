<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Blog;
use Illuminate\Support\Carbon;


class BlogStatsWidget extends BaseWidget
{

    protected function getStats(): array
    {
        $query = Blog::query();
        $totalPosts = (clone $query)->count();
        $publishedPosts = (clone $query)->where('is_active', true)->count();

        $postsThisMonth = (clone $query)->where('published_at', '>=', Carbon::now()->startOfMonth())
            ->where('published_at', '<=', Carbon::now()->endOfMonth())
            ->count();
        // Get percentage change from last month
        $postsLastMonth = (clone $query)->where('published_at', '>=', Carbon::now()->subMonth()->startOfMonth())
            ->where('published_at', '<=', Carbon::now()->subMonth()->endOfMonth())
            ->count();
        $percentageChange = $postsLastMonth > 0
            ? round((($postsThisMonth - $postsLastMonth) / $postsLastMonth) * 100)
            : 0;
        $trend = $percentageChange >= 0 ? 'arttı' : 'azaldı';


        return [
            Stat::make('Toplam Blog Yazı Sayısı', $totalPosts)
                ->description('Tüm blog yazıları')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('gray'),
            Stat::make('Bu Ay Yayınlanan Blog Yazı Sayısı', $postsThisMonth)
                ->description(' önceki aya göre ' . $percentageChange . '% ' . $trend)
                ->descriptionIcon($trend === 'arttı' ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($trend === 'arttı' ? 'success' : 'danger'),
            Stat::make('Yayınlanan Yazılar', $publishedPosts)
                ->description('Sitede yayınlananlar')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}
