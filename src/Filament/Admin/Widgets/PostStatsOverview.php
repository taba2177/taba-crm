<?php

namespace Taba\Crm\Filament\Admin\Widgets;

use Taba\Crm\Models\Post;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PostStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(__('Total Posts'), Post::count())
                ->description(__('All posts in the database'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make(__('Published Posts'), Post::where('is_published', true)->count())
                ->description(__('All published posts'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make(__('Draft Posts'), Post::where('is_published', false)->count())
                ->description(__('All draft posts'))
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),
        ];
    }
}
