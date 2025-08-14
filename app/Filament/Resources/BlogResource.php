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
use Filament\Forms\Components\BelongsToSelect;
use Filament\Notifications\Notification;
use Filament\Forms\Get;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Illuminate\Database\Eloquent\Model;
use Str;





class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Blog Yazıları';
    protected static ?string $navigationGroup = 'Blog';
        protected static ?int $navigationSort = 1;




    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Blog İçeriği')
                            ->description('Blog yazınızın ana içeriği')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->label('Başlık')
                                    ->live(onBlur: true)
                                    ->maxLength(255)
                                    ->placeholder('Başlık Girin')
                                    ->afterStateUpdated(fn(string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                                Forms\Components\TextInput::make('slug')
                                    ->disabled()
                                    ->label('URL')
                                    ->dehydrated()
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(Blog::class, 'slug', ignoreRecord: true)
                                    ->helperText('URL başlığınızdan otomatik olarak oluşturulacak.')
                                    ->suffixAction(function (string $operation) {
                                        if ($operation === 'edit') {
                                            return Forms\Components\Actions\Action::make('editSlug')
                                                ->icon('heroicon-o-pencil-square')
                                                ->modalHeading('URL düzenle')
                                                ->modalDescription('Bu yazının URL’sini özelleştirin. Sadece küçük harfler, rakamlar ve tireler kullanın.')
                                                ->modalIcon('heroicon-o-link')
                                                ->modalSubmitActionLabel('URL’yi Güncelle')
                                                ->form([
                                                    Forms\Components\TextInput::make('new_slug')
                                                        ->hiddenLabel()
                                                        ->required()
                                                        ->maxLength(255)
                                                        ->live(debounce: 500)
                                                        ->afterStateUpdated(function (string $state, Forms\Set $set) {
                                                            $set('new_slug', Str::slug($state));
                                                        })
                                                        ->unique(Blog::class, 'slug', ignoreRecord: true)
                                                        ->helperText('Yazdıkça URL otomatik olarak biçimlendirilecektir.')
                                                ])
                                                ->action(function (array $data, Forms\Set $set) {
                                                    $set('slug', $data['new_slug']);

                                                    Notification::make()
                                                        ->title('URL güncellendi')
                                                        ->success()
                                                        ->send();
                                                });
                                        }
                                        return null;
                                    }),

                                Forms\Components\Textarea::make('excerpt')
                                    ->required()
                                    ->label('Özet')
                                    ->placeholder('Bu yazının kısa bir özetini veya alıntısını sağlayın')
                                    ->helperText('Bu, blog listeleme sayfasında görünecektir')
                                    ->rows(5),

                                Forms\Components\RichEditor::make('content')
                                    ->toolbarButtons([
                                        'attachFiles',
                                        'blockquote',
                                        'bold',
                                        'bulletList',
                                        'codeBlock',
                                        'h1',
                                        'h2',
                                        'h3',
                                        'italic',
                                        'link',
                                        'orderedList',
                                        'redo',
                                        'strike',
                                        'underline',
                                        'undo',
                                    ])
                                    ->required()
                                    ->label('İçerik')
                                    ->placeholder('Buraya yazı içeriğinizi yazın...')
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('blog/content-uploads')
                                    ->columnSpanFull()
                                    ->maxLength(65535)
                                    ->helperText('Yukarıdaki araç çubuğunu kullanarak içeriğinizi biçimlendirin')
                                    ->hint(function (Get $get): string {
                                        $wordCount = str_word_count(strip_tags($get('content')));
                                        $readingTime = ceil($wordCount / 200); // Assuming 200 words per minute
                                        return "{$wordCount} kelime | ~{$readingTime} dk okuma süresi";
                                    })
                                    ->extraInputAttributes(['style' => 'min-height: 500px;']),
                            ]),

                        Forms\Components\Section::make('Medya')
                            ->label('Medya')
                            ->description('Gönderiniz için görsel unsurlar')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('thumbnail')
                                    ->label('Dış Resim')
                                    ->collection('thumbnails')
                                    ->image()
                                    ->imageResizeMode('contain')
                                    ->imageCropAspectRatio('16:9')
                                    ->imageResizeTargetWidth('1200')
                                    ->imageResizeTargetHeight('675')
                                    ->helperText('Bu resim, gönderi listeleme sayfalarında ve sosyal paylaşımlarda belirgin bir şekilde görüntülenecektir (16:9 oranı önerilir)')
                                    ->downloadable()
                                    ->responsiveImages(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Görünürlük')
                            ->description('Bu gönderinin nasıl görüneceğini kontrol edin')
                            ->icon('heroicon-o-eye')
                            ->schema([
                                Forms\Components\Select::make('is_active')
                                    ->options([
                                        1 => 'Aktif',
                                        0 => 'Pasif',
                                    ])
                                    ->default(1)
                                    ->label('Durum')
                                    ->live()
                                    ->required(),
                            ]),

                        Forms\Components\Section::make('Kategorizasyon')
                            ->description('Bu gönderiyi organize edin ve sınıflandırın')
                            ->icon('heroicon-o-tag')
                            ->schema([
                                Forms\Components\Select::make('blog_category_id')
                                    ->label('Kategori')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Kategori Seçin')
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Kategori Adı')
                                            ->required(),
                                        Forms\Components\Hidden::make('slug')
                                            ->default(fn($state) => Str::slug($state['name'] ?? '')),
                                    ])
                                    ->required(),

                                SpatieTagsInput::make('tags')
                                    ->label('Etiketler')
                                    ->placeholder('Etiket ekleyin')
                                    ->helperText('Arama ve filtreleme için virgülle ayrılmış etiketler'),
                                Forms\Components\TextInput::make('order')
                                    ->label('Sıra')
                                    ->numeric()
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Sıra numarasını girin'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Başlık')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Durum')
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('views')
                    ->label('Görüntülenme Sayısı')
                    ->sortable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Yayınlanma Tarihi')
                    ->date()
                    ->sortable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('is_active')
                    ->options([
                        1 => 'Aktif',
                        0 => 'Pasif',
                    ]),
                Tables\Filters\SelectFilter::make('blog_category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Düzenle'),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('publishSelected')
                    ->label('Yayınla')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function ($records): void {
                        foreach ($records as $record) {
                            $record->update([
                                'is_active' => true,
                                'published_at' => now(),
                            ]);
                        }
                        Notification::make()
                            ->title('Selected posts published successfully')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation(),

                Tables\Actions\BulkAction::make('deactivateSelected')
                    ->label('Pasifleştir')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->action(function ($records): void {
                        foreach ($records as $record) {
                            $record->update([
                                'is_active' => false,
                                'published_at' => null,
                            ]);
                        }
                        Notification::make()
                            ->title('Selected posts deactivated successfully')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation(),

                Tables\Actions\DeleteBulkAction::make()
                    ->label('Sil')
                    ->modalHeading('Blog Yazılarını Sil')
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
