<?php
// src/Filament/Client/Widgets/AdvertisementsOverview.php

namespace Taba\Crm\Filament\Client\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Taba\Crm\Models\Post;
use Taba\Crm\Models\PostCategory;

class AdvertisementsOverview extends BaseWidget
{
    protected static ?string $heading = 'المنشورات حسب القسم';
    protected static ?int $sort = 6;

    protected function getStats(): array
    {
        return PostCategory::withCount(['posts' => fn($q) => $q->published()])
            ->orderByDesc('posts_count')
            ->limit(5)
            ->get()
            ->map(fn($cat) => Stat::make($cat->name, $cat->posts_count))
            ->toArray();
    }
}