<?php

namespace App\Filament\Resources\FaqResource\Pages;

use App\Filament\Resources\FaqResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFaq extends EditRecord
{
    protected static string $resource = FaqResource::class;

    protected static ?string $title = 'SSS Düzenle';

    public function getBreadcrumbs(): array
    {
        return [];
    }


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label("SSS Sil")->modalHeading("SSS Sil"),
        ];
    }
}
