<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamResource\Pages;
use App\Filament\Resources\TeamResource\RelationManagers;
use App\Models\Team;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\TextInput;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Ekip Üyeleri';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Adı ve Soyadı')
                    ->required()
                    ->maxLength(255),
                TextInput::make('position')
                    ->label('Pozisyonu')
                    ->maxLength(255),
                FileUpload::make('photo')
                    ->label('Fotoğrafı')
                    ->image()
                    ->imageEditor()
                    ->disk('public')
                    ->directory('teams')
                    ->preserveFilenames(),
                Textarea::make('biography')
                    ->label('Biyografi')
                    ->autosize()
                    ->maxLength(65535),
                TextInput::make('email')
                    ->label('E-posta')
                    ->email()
                    ->maxLength(255),
                TextInput::make('phone')
                    ->label('Telefon Numarası')
                    ->tel()
                    ->maxLength(20)
                    ->regex('/^\+?[0-9\s\-]{7,20}$/'),
                TextInput::make('linkedin')
                    ->label('LinkedIn Kullanıcı Adı')
                    ->maxLength(255),
                Toggle::make('is_active')
                    ->label('Durum')
                    ->default(true)
                    ->required(),
                TextInput::make('order')
                    ->label('Sıra')
                    ->numeric()
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Adı ve Soyadı'),
                Tables\Columns\TextColumn::make('position')->label('Pozisyonu'),
                Tables\Columns\ImageColumn::make('photo')->label('Fotoğrafı')->circular(),
                Tables\Columns\TextColumn::make('email')->label('E-posta'),
                Tables\Columns\ToggleColumn::make('is_active')->label('Durum'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Düzenle')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Sil'),
                ])->label('Toplu İşlemler'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeams::route('/'),
            'create' => Pages\CreateTeam::route('/create'),
            'edit' => Pages\EditTeam::route('/{record}/edit'),
        ];
    }
}
