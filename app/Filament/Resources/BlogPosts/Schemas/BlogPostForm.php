<?php

namespace App\Filament\Resources\BlogPosts\Schemas;

use App\Models\BlogCategory;
use App\Models\BlogImage;
use App\Models\BlogTag;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class BlogPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Meta')
                    ->columnSpanFull()
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                    ])
                    ->schema([
                        Select::make('blog_category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Set $set): mixed => $set('tags', []))
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (?string $state, callable $set, callable $get): void {
                                        if (($get('slug') ?? '') !== '') {
                                            return;
                                        }

                                        $set('slug', $state ? Str::slug($state) : null);
                                    }),
                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(table: BlogCategory::class, column: 'slug'),
                                Select::make('accent')
                                    ->required()
                                    ->options([
                                        'primary' => 'Teal',
                                        'amber' => 'Amber',
                                        'secondary' => 'Violet',
                                        'slate' => 'Slate',
                                        'sky' => 'Sky',
                                        'rose' => 'Rose',
                                    ])
                                    ->default('primary'),
                            ]),
                        Select::make('tags')
                            ->relationship(
                                'tags',
                                'name',
                                function (Builder $query, Get $get): Builder {
                                    $categoryId = $get->integer('blog_category_id', isNullable: true);

                                    if ($categoryId === null) {
                                        return $query->whereRaw('1 = 0');
                                    }

                                    return $query->whereHas('categories', fn (Builder $query): Builder => $query->whereKey($categoryId));
                                },
                            )
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->helperText('Tags are filtered by the selected category.')
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (?string $state, callable $set, callable $get): void {
                                        if (($get('slug') ?? '') !== '') {
                                            return;
                                        }

                                        $set('slug', $state ? Str::slug($state) : null);
                                    }),
                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(table: BlogTag::class, column: 'slug'),
                            ]),
                        DateTimePicker::make('published_at')
                            ->label('Publish at')
                            ->seconds(false)
                            ->helperText('Leave empty for drafts. Future dates are scheduled.'),
                        Toggle::make('is_featured')
                            ->label('Featured on homepage')
                            ->helperText('Shows in the homepage “Featured” section (published posts only).'),
                        Select::make('featured_blog_image_id')
                            ->label('Featured image (library)')
                            ->relationship('featuredImage', 'name')
                            ->getOptionLabelFromRecordUsing(fn (BlogImage $record): string => $record->name ?: $record->path)
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->helperText('Pick an existing image from Blog Images. Choosing one will clear the upload field below.')
                            ->live()
                            ->afterStateUpdated(fn (Set $set): mixed => $set('featured_image_path', null))
                            ->columnSpanFull(),
                        FileUpload::make('featured_image_path')
                            ->label('Featured image')
                            ->disk('public')
                            ->directory('blog/featured')
                            ->visibility('public')
                            ->image()
                            ->imageEditor()
                            ->helperText('Upload a new image. Uploading will clear any selected library image above.')
                            ->afterStateUpdated(fn (Set $set, mixed $state): mixed => $state ? $set('featured_blog_image_id', null) : null)
                            ->columnSpanFull(),
                    ]),

                Section::make('Post')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (?string $state, callable $set, callable $get): void {
                                if (($get('slug') ?? '') !== '') {
                                    return;
                                }

                                $set('slug', $state ? Str::slug($state) : null);
                            }),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Textarea::make('excerpt')
                            ->required()
                            ->rows(3)
                            ->maxLength(500),
                        MarkdownEditor::make('body_markdown')
                            ->required()
                            ->minHeight('28rem')
                            ->placeholder("Write your post in Markdown...\n\nTip: use fenced code blocks for snippets:\n```php\n// code here\n```")
                            ->helperText('Markdown supported. Use fenced code blocks for snippets (```lang ... ```).'),
                    ]),
            ]);
    }
}
