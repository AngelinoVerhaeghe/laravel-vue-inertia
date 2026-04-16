<?php

namespace App\Filament\Resources\BlogImages\Tables;

use App\Models\BlogImage;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BlogImagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('preview')
                    ->getStateUsing(fn (BlogImage $record): string => $record->path)
                    ->disk(fn (BlogImage $record): string => $record->disk)
                    ->label('Preview')
                    ->square(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->placeholder('(no name)'),
                TextColumn::make('path')
                    ->label('Path')
                    ->toggleable()
                    ->limit(40),
                TextColumn::make('created_at')
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
