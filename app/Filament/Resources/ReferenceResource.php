<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReferenceResource\Pages;
use App\Filament\Resources\ReferenceResource\RelationManagers;
use App\Models\Reference;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use function PHPUnit\Framework\directoryExists;

class ReferenceResource extends Resource
{
    protected static ?string $model = Reference::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Referanslar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('client_name')
                    ->label('Müşteri Adı'),
                Forms\Components\TextInput::make('company')
                    ->label('Şirket')
                    ->required(),
                Forms\Components\Textarea::make('testimonial')
                    ->label('Referan Yazısı'),
                Forms\Components\FileUpload::make('photo')
                    ->label('Fotoğraf')
                    ->image()
                    ->imageEditor()
                    ->disk('public')
                    ->directory('references')
                    ->preserveFilenames(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Durum')
                    ->default(true)
                    ->required(),
                Forms\Components\TextInput::make('order')
                    ->label('Sıra')
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client_name')
                    ->label('Müşteri Adı'),
                Tables\Columns\TextColumn::make('company')
                    ->label('Şirket'),
                Tables\Columns\TextColumn::make('testimonial')
                    ->label('Referan Yazısı'),
                Tables\Columns\ImageColumn::make('photo')
                    ->label('Fotoğraf'),
                Tables\Columns\BooleanColumn::make('is_active')
                    ->label('Durum'),
                Tables\Columns\TextColumn::make('order')
                    ->label('Sıra'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Düzenle'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Sil')->modalHeading('Referans Sil'),
                ])->label('Toplu İşlemler'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReferences::route('/'),
            'create' => Pages\CreateReference::route('/olustur'),
            'edit' => Pages\EditReference::route('/{record}/duzenle'),
        ];
    }
}
