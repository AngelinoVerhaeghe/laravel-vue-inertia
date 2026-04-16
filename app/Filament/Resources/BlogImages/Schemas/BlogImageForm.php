<?php

namespace App\Filament\Resources\BlogImages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BlogImageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Image')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->maxLength(255)
                            ->helperText('Optional label for your own reference.'),
                        Hidden::make('disk')
                            ->default('public'),
                        FileUpload::make('path')
                            ->disk('public')
                            ->directory('blog/library')
                            ->visibility('public')
                            ->image()
                            ->imageEditor()
                            ->required(),
                    ]),
            ]);
    }
}
