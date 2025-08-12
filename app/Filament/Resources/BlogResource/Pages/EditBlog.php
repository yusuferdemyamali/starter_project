<?php

namespace App\Filament\Resources\BlogResource\Pages;

use App\Filament\Resources\BlogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBlog extends EditRecord
{
    protected static string $resource = BlogResource::class;

    protected static ?string $title = 'Blog Yazısı Düzenle';

    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label("Blog Yazısını Sil")->modalHeading("Blog Yazısını Sil"),
        ];
    }
}
