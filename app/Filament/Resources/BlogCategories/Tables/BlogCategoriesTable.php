<?php

namespace App\Filament\Resources\BlogCategories\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BlogCategoriesTable
{
    /**
     * @return array<string, string>
     */
    private static function accentLabels(): array
    {
        return [
            'primary' => 'Teal',
            'amber' => 'Amber',
            'secondary' => 'Violet',
            'slate' => 'Slate',
            'sky' => 'Sky',
            'rose' => 'Rose',
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function accentBadgeColors(): array
    {
        return [
            'primary' => 'teal',
            'amber' => 'amber',
            'secondary' => 'violet',
            'slate' => 'slate',
            'sky' => 'sky',
            'rose' => 'rose',
        ];
    }

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->sortable(),
                TextColumn::make('accent')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => self::accentLabels()[$state ?? 'primary'] ?? 'Teal')
                    ->color(fn (?string $state): string => self::accentBadgeColors()[$state ?? 'primary'] ?? 'teal')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
                    ->iconButton()
                    ->icon(Heroicon::OutlinedEllipsisVertical),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
