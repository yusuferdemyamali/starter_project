<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqResource\Pages;
use App\Models\Faq;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationLabel = 'SSS';

    protected static ?string $navigationGroup = 'Sayfalar';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('question')
                                    ->label('Soru')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\RichEditor::make('answer')
                                    ->label('Cevap')
                                    ->required()
                                    ->maxLength(65535)
                                    ->columnSpanFull(),
                            ])->columnSpan(2),

                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('Durum')
                                    ->schema([
                                        Forms\Components\Toggle::make('is_active')
                                            ->label('Aktif')
                                            ->default(true),
                                        Forms\Components\TextInput::make('order')
                                            ->label('Sıra')
                                            ->numeric()
                                            ->unique(ignoreRecord: true)
                                            ->required(),
                                    ]),
                            ])->columnSpan(1),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->orderBy('order'))
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
                    Tables\Actions\DeleteBulkAction::make()->label('SSS Sil')->modalHeading('SSS Sil'),
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
