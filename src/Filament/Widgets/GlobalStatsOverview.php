<?php

namespace Taba\Crm\Filament\Widgets;

use Taba\Crm\Models\User;
use Taba\Crm\Models\Post;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class GlobalStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(__('Total Users'), User::count())
                ->description(__('All users in the database'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make(__('Total Posts'), Post::count())
                ->description(__('All posts in the database'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            // Stat::make(__('Total Pages'), Page::count())
            //     ->description(__('All pages in the database'))
            //     ->descriptionIcon('heroicon-m-arrow-trending-up')
            //     ->color('success'),
        ];
    }
}
