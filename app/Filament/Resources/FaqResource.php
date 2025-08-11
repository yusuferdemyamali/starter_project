<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqResource\Pages;
use App\Filament\Resources\FaqResource\RelationManagers;
use App\Models\Faq;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'SSS';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('question')
                    ->label('Soru')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('answer')
                    ->label('Cevap')
                    ->maxLength(65535)
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Durum')
                    ->default(true),
                Forms\Components\TextInput::make('order')
                    ->label('Sıra')
                    ->numeric()
                    ->unique(ignoreRecord: true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question')
                    ->label('Soru')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Durum')
                    ->sortable(),
                Tables\Columns\TextColumn::make('order')
                    ->label('Sıra')
                    ->sortable(),
            ])
            ->searchPlaceholder('SSS Ara...')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Düzenle'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label("SSS Sil")->modalHeading('SSS Sil'),
                ])->label('Toplu İşlemler'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/olustur'),
            'edit' => Pages\EditFaq::route('/{record}/duzenle'),
        ];
    }
}
