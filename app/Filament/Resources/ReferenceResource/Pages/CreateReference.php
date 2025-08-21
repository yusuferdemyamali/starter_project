<?php

namespace App\Filament\Resources\ReferenceResource\Pages;

use App\Filament\Resources\ReferenceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReference extends CreateRecord
{
    protected static string $resource = ReferenceResource::class;

    protected static ?string $title = 'Yeni Referans Ekle';

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
