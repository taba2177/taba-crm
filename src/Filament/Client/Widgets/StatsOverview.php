<?php
// src/Filament/Client/Widgets/StatsOverview.php

namespace Taba\Crm\Filament\Client\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Taba\Crm\Models\ContactEntry;
use Taba\Crm\Models\Post;
use Taba\Crm\Models\PostCategory;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        return [
            Stat::make('المنشورات المنشورة', Post::published()->count()),
            Stat::make('الأقسام النشطة', PostCategory::count()),
            Stat::make('الرسائل الكلية', ContactEntry::count()),
        ];
    }
}