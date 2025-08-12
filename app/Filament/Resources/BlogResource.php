<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Filament\Resources\BlogResource\RelationManagers;
use App\Models\Blog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Str;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Blog Yazıları';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->label('Başlık')
                    ->maxLength(255)
                    ->live(true)
                    ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                        $set('slug', Str::slug($state));
                    }),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->label('URL'),
                Forms\Components\RichEditor::make('content')
                    ->required()
                    ->label('İçerik')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('excerpt')
                    ->nullable()
                    ->label('Özet'),
                Forms\Components\FileUpload::make('thumbnail')
                    ->nullable()
                    ->label('Dış Resim')
                    ->image()
                    ->imageEditor()
                    ->disk('public')
                    ->directory('blogs/thumbnails')
                    ->preserveFilenames(),
                Forms\Components\TextInput::make('order')
                    ->required()
                    ->numeric()
                    ->label('Sıra'),
                Forms\Components\TextInput::make('author')
                    ->nullable()
                    ->label('Yazar'),
                Forms\Components\DateTimePicker::make('published_at')
                    ->label('Yayınlanma Tarihi')
                    ->default(now()),
                Forms\Components\Toggle::make('is_active')
                    ->default(true)
                    ->label('Durum'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Başlık'),
                Tables\Columns\ImageColumn::make('thumbnail')->label('Dış Resim'),
                Tables\Columns\TextColumn::make('author')->label('Yazar'),
                Tables\Columns\TextColumn::make('published_at')->label('Yayınlanma Tarihi'),
                Tables\Columns\ToggleColumn::make('is_active')->label('Durum'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Düzenle'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Sil')->modalHeading('Blog Yazılarını Sil')
                ])->label('Toplu İşlemler'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/olustur'),
            'edit' => Pages\EditBlog::route('/{record}/duzenle'),
        ];
    }
}
