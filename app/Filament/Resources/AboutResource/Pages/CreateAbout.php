<?php

namespace App\Filament\Resources\AboutResource\Pages;

use App\Filament\Resources\AboutResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAbout extends CreateRecord
{
    protected static string $resource = AboutResource::class;

    protected static bool $canCreateAnother = false;

    protected static ?string $title = 'Hakkımızda Yazısı Ekle';

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
