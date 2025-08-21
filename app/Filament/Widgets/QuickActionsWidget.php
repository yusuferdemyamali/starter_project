<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    protected static string $view = 'filament.widgets.quick-actions';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 2;

    protected function getViewData(): array
    {
        return [
            'actions' => [
                [
                    'label' => 'Yeni Blog Yazısı',
                    'url' => route('filament.admin.resources.blogs.create'),
                    'icon' => 'heroicon-o-document-plus',
                    'color' => 'primary',
                    'description' => 'Blog yazısı ekleyin',
                ],
                [
                    'label' => 'Yeni Ürün',
                    'url' => route('filament.admin.resources.products.create'),
                    'icon' => 'heroicon-o-cube',
                    'color' => 'warning',
                    'description' => 'Ürün kataloğuna ekleyin',
                ],
                [
                    'label' => 'Yeni Referans',
                    'url' => route('filament.admin.resources.references.create'),
                    'icon' => 'heroicon-o-star',
                    'color' => 'success',
                    'description' => 'Müşteri referansı ekleyin',
                ],
                [
                    'label' => 'Yeni Ekip Üyesi',
                    'url' => route('filament.admin.resources.teams.create'),
                    'icon' => 'heroicon-o-user-plus',
                    'color' => 'info',
                    'description' => 'Ekip üyesi ekleyin',
                ],
                [
                    'label' => 'Site Ayarları',
                    'url' => route('filament.admin.resources.site-settings.index'),
                    'icon' => 'heroicon-o-cog-6-tooth',
                    'color' => 'gray',
                    'description' => 'Genel ayarları düzenleyin',
                ],
                [
                    'label' => 'Galeri Yönetimi',
                    'url' => route('filament.admin.resources.galleries.index'),
                    'icon' => 'heroicon-o-photo',
                    'color' => 'purple',
                    'description' => 'Görselleri yönetin',
                ],
            ],
        ];
    }
}
