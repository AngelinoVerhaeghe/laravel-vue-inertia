<?php

namespace App\Filament\Resources\BlogPosts\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BlogPostsTable
{
    /**
     * Maps a category accent token (stored on BlogCategory) to a Filament badge
     * color name. Public so widgets (e.g. LatestBlogPosts) can reuse the same
     * source of truth instead of redefining the palette.
     *
     * @return array<string, string>
     */
    public static function accentBadgeColors(): array
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
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(60),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->badge()
                    ->color(function (Model $record): string {
                        /** @var mixed $accent */
                        $accent = $record->category?->accent;

                        return self::accentBadgeColors()[is_string($accent) ? $accent : 'primary'] ?? 'teal';
                    }),
                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->sortable(),
                IconColumn::make('is_headline')
                    ->label('Headline')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('published_at')
                    ->label('Publish')
                    ->sortable()
                    ->dateTime('Y-m-d H:i')
                    ->placeholder('Draft'),
                TextColumn::make('reading_time_minutes')
                    ->label('Read')
                    ->suffix(' min')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('draft')
                    ->label('Drafts')
                    ->query(fn (Builder $query): Builder => $query->whereNull('published_at')),
                Filter::make('published')
                    ->label('Published')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('published_at')->where('published_at', '<=', now())),
                Filter::make('scheduled')
                    ->label('Scheduled')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('published_at')->where('published_at', '>', now())),
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
