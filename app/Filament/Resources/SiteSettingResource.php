<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteSettingResource\Pages;
use App\Filament\Resources\SiteSettingResource\RelationManagers;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Site Yönetimi';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'site_name';

    protected static bool $shouldRegisterNavigation = true;
    protected static ?string $navigationLabel = 'Ayarlar';


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
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('site_name')
                            ->label('Site Adı')
                            ->required(),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('site_email')
                                    ->label('E-posta Adresi')
                                    ->email(),
                                Forms\Components\TextInput::make('site_phone')
                                    ->label('Telefon Numarası')
                                    ->tel(),
                                Forms\Components\FileUpload::make('site_logo')
                                    ->label('Logo')
                                    ->image()
                                    ->imageEditor()
                                    ->disk('public')
                                    ->directory('settings')
                                    ->preserveFilenames(),
                                Forms\Components\FileUpload::make('site_favicon')
                                    ->label('Site Favicon')
                                    ->image()
                                    ->imageEditor()
                                    ->disk('public')
                                    ->directory('settings')
                                    ->preserveFilenames(),
                                Forms\Components\TextInput::make('site_address')
                                    ->label('Adres'),
                                Forms\Components\TextInput::make('site_facebook_url')
                                    ->label('Facebook URL')
                                    ->url(),
                                Forms\Components\TextInput::make('site_twitter_url')
                                    ->label('Twitter URL')
                                    ->url(),
                                Forms\Components\TextInput::make('site_linkedin_url')
                                    ->label('LinkedIn URL')
                                    ->url(),
                                Forms\Components\TextInput::make('site_instagram_url')
                                    ->label('Instagram URL')
                                    ->url(),
                                Forms\Components\TextInput::make('site_youtube_url')
                                    ->label('YouTube URL')
                                    ->url(),
                                Forms\Components\Textarea::make('site_working_hours')
                                    ->label('Çalışma Saatleri')
                                    ->placeholder('Çalışma saatlerini girin...'),
                                Forms\Components\Textarea::make('site_maps_embed')
                                    ->label('Harita Embed Kodu')
                                    ->placeholder('Harita embed kodunu girin...'),
                                Forms\Components\TextInput::make('site_seo_title')
                                    ->label('SEO Başlığı')
                                    ->placeholder('SEO başlığını girin...'),
                                Forms\Components\Textarea::make('site_seo_description')
                                    ->label('SEO Açıklaması')
                                    ->placeholder('SEO açıklamasını girin...'),
                                Forms\Components\Textarea::make('site_seo_keywords')
                                    ->label('SEO Anahtar Kelimeleri')
                                    ->placeholder('SEO anahtar kelimelerini girin...'),
                            ]),
                    ]),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('site_name')->label('Site Adı'),
                Tables\Columns\TextColumn::make('site_email')->label('E-posta Adresi'),
                Tables\Columns\TextColumn::make('site_phone')->label('Telefon Numarası'),
                Tables\Columns\TextColumn::make('site_address')->label('Adres'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Sil')->modalHeading('Site Ayarlarını Sil')
                ])->label('Toplu İşlemler'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiteSettings::route('/'),
            'create' => Pages\CreateSiteSetting::route('/create'),
            'edit' => Pages\EditSiteSetting::route('/{record}/edit'),
        ];
    }
}
