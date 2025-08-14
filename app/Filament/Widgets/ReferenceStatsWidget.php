<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Reference;
use Illuminate\Support\Carbon;


class ReferenceStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $query = Reference::query();
        $totalReferences = (clone $query)->count();
        $referencesThisMonth = (clone $query)->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->where('created_at', '<=', Carbon::now()->endOfMonth())
            ->count();
        // Get percentage change from last month
        $referencesLastMonth = (clone $query)->where('created_at', '>=', Carbon::now()->subMonth()->startOfMonth())
            ->where('created_at', '<=', Carbon::now()->subMonth()->endOfMonth())
            ->count();
        $percentageChange = $referencesLastMonth > 0
            ? round((($referencesThisMonth - $referencesLastMonth) / $referencesLastMonth) * 100)
            : 0;
        $trend = $percentageChange >= 0 ? 'arttı' : 'azaldı';


        return [
            Stat::make('Toplam Referans Sayısı', $totalReferences)
                ->description('Tüm referanslar')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('gray'),
            Stat::make('Bu Ay Yayınlanan Referans Sayısı', $referencesThisMonth)
                ->description(' önceki aya göre ' . $percentageChange . '% ' . $trend)
                ->descriptionIcon($trend === 'arttı' ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($trend === 'arttı' ? 'success' : 'danger'),
            Stat::make('Yeni Referans Ekle', '')
                ->description('Hızlıca yeni referans ekleyin')
                ->url(route('filament.admin.resources.references.create'))
                ->color('primary')
                ->descriptionIcon('heroicon-m-plus-circle'),
        ];
    }
}
