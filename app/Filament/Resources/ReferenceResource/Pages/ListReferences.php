<?php

namespace App\Filament\Resources\ReferenceResource\Pages;

use App\Filament\Resources\ReferenceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReferences extends ListRecords
{
    protected static string $resource = ReferenceResource::class;

    protected static ?string $title = 'Referanslar';

    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label("Yeni Referans Ekle"),
        ];
    }
}
