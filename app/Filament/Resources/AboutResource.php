<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AboutResource\Pages;
use App\Filament\Resources\AboutResource\RelationManagers;
use App\Models\About;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AboutResource extends Resource
{
    protected static ?string $model = About::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Hakkımızda';
    protected static bool $shouldRegisterNavigation = true;

    public static function getNavigationUrl(): string
    {
        $record = static::getModel()::first();

        if ($record) {
            return static::getUrl('edit', ['record' => $record]);
        }

        return static::getUrl('create');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Başlık')
                    ->maxLength(255),
                Forms\Components\RichEditor::make('content')
                    ->label('İçerik')
                    ->columnSpanFull()
                    ->required(),
                Forms\Components\FileUpload::make('image')
                    ->label('Görsel')
                    ->image()
                    ->imageEditor()
                    ->disk('public')
                    ->directory('abouts')
                    ->preserveFilenames(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Sil')->modalHeading('Hakkımızda Yazısı Silinecek')
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAbouts::route('/'),
            'create' => Pages\CreateAbout::route('/create'),
            'edit' => Pages\EditAbout::route('/{record}/edit'),
        ];
    }
}
