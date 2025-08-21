<?php

namespace App\Filament\Widgets;

use App\Models\Blog;
use App\Models\Product;
use App\Models\Reference;
use Filament\Widgets\ChartWidget;

class ContentDistributionChart extends ChartWidget
{
    protected static ?string $heading = 'İçerik Dağılımı';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $blogCount = Blog::count();
        $productCount = Product::count();
        $referenceCount = Reference::count();

        return [
            'datasets' => [
                [
                    'data' => [$blogCount, $productCount, $referenceCount],
                    'backgroundColor' => [
                        'rgb(59, 130, 246)',  // Blue
                        'rgb(245, 158, 11)',  // Yellow/Orange
                        'rgb(34, 197, 94)',   // Green
                    ],
                    'borderColor' => [
                        'rgb(59, 130, 246)',
                        'rgb(245, 158, 11)',
                        'rgb(34, 197, 94)',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => ['Blog Yazıları', 'Ürünler', 'Referanslar'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
            'maintainAspectRatio' => false,
            'responsive' => true,
        ];
    }
}
