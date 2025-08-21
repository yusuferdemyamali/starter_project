<?php

namespace App\Filament\Resources\BlogResource\Pages;

use App\Filament\Resources\BlogResource;
use App\Filament\Resources\BlogResource\Widgets\BlogPostStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBlogs extends ListRecords
{
    protected static string $resource = BlogResource::class;

    protected static ?string $title = 'Blog Yazıları';

    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Yeni Blog Yazısı Ekle'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            BlogPostStats::class,
        ];
    }
}
