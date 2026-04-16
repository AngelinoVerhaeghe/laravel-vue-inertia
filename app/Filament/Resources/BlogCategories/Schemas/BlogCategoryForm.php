<?php

namespace App\Filament\Resources\BlogCategories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BlogCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Category')
                    ->columns(2)
                    ->schema([
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
                            ->unique(ignoreRecord: true),
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
            ]);
    }
}
