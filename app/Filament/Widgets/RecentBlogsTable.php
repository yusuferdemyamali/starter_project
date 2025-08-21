<?php

namespace App\Filament\Widgets;

use App\Models\Blog;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentBlogsTable extends BaseWidget
{
    protected static ?string $heading = 'Son Eklenen Blog Yazıları';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        return Blog::query()->latest()->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            ImageColumn::make('thumbnail')
                ->label('Görsel')
                ->size(60)
                ->circular()
                ->defaultImageUrl(url('/images/no-image.png')),

            TextColumn::make('title')
                ->label('Başlık')
                ->searchable()
                ->sortable()
                ->limit(50)
                ->tooltip(function (TextColumn $column): ?string {
                    $state = $column->getState();

                    return strlen($state) > 50 ? $state : null;
                }),

            TextColumn::make('blogCategory.name')
                ->label('Kategori')
                ->badge()
                ->color('primary'),

            TextColumn::make('author')
                ->label('Yazar')
                ->icon('heroicon-m-user'),

            TextColumn::make('is_active')
                ->label('Durum')
                ->formatStateUsing(fn (string $state): string => $state ? 'Aktif' : 'Pasif')
                ->badge()
                ->color(fn (string $state): string => $state ? 'success' : 'danger'),

            TextColumn::make('published_at')
                ->label('Yayın Tarihi')
                ->dateTime('d.m.Y H:i')
                ->sortable(),

            TextColumn::make('views')
                ->label('Görüntülenme')
                ->numeric()
                ->sortable()
                ->icon('heroicon-m-eye'),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('edit')
                ->label('Düzenle')
                ->icon('heroicon-m-pencil-square')
                ->url(fn (Blog $record): string => route('filament.admin.resources.blogs.edit', $record))
                ->color('warning'),
        ];
    }
}
