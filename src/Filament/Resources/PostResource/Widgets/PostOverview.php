<?php

namespace Taba\Crm\Filament\Resources\PostResource\Widgets;

use Taba\Crm\Models\Post;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class PostOverview extends BaseWidget
{
    /**
     * The widget stats.
     */
    protected function getStats(): array
    {
        $posts = Post::count();
        $published = Post::published()->count();
        $drafts = Post::drafts()->count();

        return [
            Stat::make(__('Total Posts'), Number::format($posts))
                ->description(__('The total number of posts'))
                ->color('primary')
                ->icon('heroicon-o-book-open'),

            Stat::make(__('Published Posts'), Number::format($published))
                ->description(__('The total number of published posts'))
                ->color('success')
                ->icon('heroicon-o-check-circle'),

            Stat::make(__('Draft Posts'), Number::format($drafts))
                ->description(__('The total number of draft posts'))
                ->color('warning')
                ->icon('heroicon-o-x-circle'),
        ];
    }
}