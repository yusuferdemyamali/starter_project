<?php

namespace App\Filament\Resources\BlogResource\Pages;

use App\Filament\Resources\BlogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBlog extends CreateRecord
{
    protected static string $resource = BlogResource::class;

    protected static ?string $title = 'Yeni Blog Yazısı Ekle';

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
