<?php

namespace App\Filament\Widgets;

use App\Models\BlogImage;
use App\Models\BlogPost;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class BlogStatsOverview extends StatsOverviewWidget
{
    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $now = now();

        $totalPosts = BlogPost::query()->count();
        $publishedPosts = BlogPost::query()
            ->whereNotNull('published_at')
            ->where('published_at', '<=', $now)
            ->count();
        $draftPosts = BlogPost::query()->whereNull('published_at')->count();
        $scheduledPosts = BlogPost::query()->whereNotNull('published_at')->where('published_at', '>', $now)->count();
        $featuredPosts = BlogPost::query()
            ->where('is_featured', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', $now)
            ->count();

        $libraryImages = BlogImage::query()->count();

        return [
            Stat::make('Total posts', Number::format($totalPosts))
                ->description('All blog posts in the system.')
                ->icon('heroicon-o-document-text')
                ->color('gray'),
            Stat::make('Published', Number::format($publishedPosts))
                ->description('Visible on the public blog.')
                ->icon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Drafts', Number::format($draftPosts))
                ->description('Not published yet.')
                ->icon('heroicon-o-pencil-square')
                ->color('warning'),
            Stat::make('Scheduled', Number::format($scheduledPosts))
                ->description('Will publish in the future.')
                ->icon('heroicon-o-clock')
                ->color('info'),
            Stat::make('Featured (published)', Number::format($featuredPosts))
                ->description('Shown on the homepage.')
                ->icon('heroicon-o-star')
                ->color('primary'),
            Stat::make('Blog images', Number::format($libraryImages))
                ->description('Images in the media library.')
                ->icon('heroicon-o-photo')
                ->color('gray'),
        ];
    }
}
