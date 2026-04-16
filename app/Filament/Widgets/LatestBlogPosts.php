<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\BlogPosts\BlogPostResource;
use App\Models\BlogPost;
use Filament\Actions\BulkActionGroup;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestBlogPosts extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => BlogPost::query()->with('category')->latest('updated_at'))
            ->columns([
                TextColumn::make('title')
                    ->label('Post')
                    ->searchable()
                    ->sortable()
                    ->limit(45)
                    ->url(fn (BlogPost $record): string => BlogPostResource::getUrl('edit', ['record' => $record]))
                    ->openUrlInNewTab(),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->sortable(),
                TextColumn::make('published_at')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(function (?string $state, BlogPost $record): string {
                        if ($record->published_at === null) {
                            return 'Draft';
                        }

                        if ($record->published_at->isFuture()) {
                            return 'Scheduled';
                        }

                        return 'Published';
                    })
                    ->color(function (BlogPost $record): string {
                        if ($record->published_at === null) {
                            return 'warning';
                        }

                        if ($record->published_at->isFuture()) {
                            return 'info';
                        }

                        return 'success';
                    }),
                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->trueColor(Color::Teal)
                    ->falseColor(Color::Gray)
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
