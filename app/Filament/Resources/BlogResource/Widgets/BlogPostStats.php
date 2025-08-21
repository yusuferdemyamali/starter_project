<?php

namespace App\Filament\Resources\BlogResource\Widgets;

use App\Models\Blog;
use App\Models\Blog\Post;
use App\Models\BlogCategory;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class BlogPostStats extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $query = Blog::query();
        // Get post counts by status
        $totalPosts = (clone $query)->count();
        $publishedPosts = (clone $query)->where('is_active', true)->count();
        $draftPosts = (clone $query)->where('is_active', false)->count();
        // Get posts published this month
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
        $trend = $percentageChange >= 0 ? 'up' : 'down';

        return [
            Stat::make('Toplam Blog Yazısı', $totalPosts)
                ->description('Tüm blog yazıları')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('gray'),
            Stat::make('Yayınlanan Yazılar', $publishedPosts)
                ->description('Sitede yayınlananlar')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Pasif Yazılar', $draftPosts)
                ->description('Arşivlenmiş Yazılar')
                ->descriptionIcon('heroicon-m-document')
                ->color('warning'),
            Stat::make('Bu Ay', $postsThisMonth)
                ->description($percentageChange.'% '.$trend.' önceki aya göre')
                ->descriptionIcon($trend === 'up' ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($trend === 'up' ? 'success' : 'danger'),
            Stat::make('Kategoriler', BlogCategory::active()->count())
                ->description('Aktif kategoriler')
                ->descriptionIcon('heroicon-m-tag')
                ->color('primary'),
        ];
    }
}
