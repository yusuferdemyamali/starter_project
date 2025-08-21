<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AboutResource\Pages;
use App\Models\About;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AboutResource extends Resource
{
    protected static ?string $model = About::class;

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';

    protected static ?string $navigationLabel = 'Hakkımızda';

    protected static ?string $navigationGroup = 'Sayfalar';

    protected static ?int $navigationSort = 1;

    protected static bool $shouldRegisterNavigation = true;

    public static function getNavigationUrl(): string
    {
        try {
            $record = static::getModel()::first();

            if ($record) {
                return static::getUrl('edit', ['record' => $record]);
            }
        } catch (\Exception $e) {
            // Hata durumunda create sayfasına yönlendir
        }

        return static::getUrl('create');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->columns(3)
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('İçerik')
                                    ->description('Hakkımızda sayfasının başlık ve metin içeriğini düzenleyin.')
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->label('Başlık')
                                            ->required()
                                            ->maxLength(255),

                                        Forms\Components\RichEditor::make('content')
                                            ->label('Metin')
                                            ->required()
                                            ->columnSpanFull(),
                                    ]),
                            ])
                            ->columnSpan(['lg' => 2]),

                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('Görsel')
                                    ->description('Sayfada gösterilecek görseli yükleyin.')
                                    ->schema([
                                        Forms\Components\FileUpload::make('image')
                                            ->label('Sayfa Görseli')
                                            ->image()
                                            ->imageEditor()
                                            ->disk('public')
                                            ->directory('abouts')
                                            ->preserveFilenames()
                                            ->helperText('En iyi görünüm için 1200x800 piksel boyutunda bir görsel yükleyin.'),
                                    ]),
                            ])
                            ->columnSpan(['lg' => 1]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->orderBy('order'))
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
                    Tables\Actions\DeleteBulkAction::make()->label('Sil')->modalHeading('Hakkımızda Yazısı Silinecek'),
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
