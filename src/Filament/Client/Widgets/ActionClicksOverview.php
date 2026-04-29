<?php
// src/Filament/Client/Widgets/ActionClicksOverview.php

namespace Taba\Crm\Filament\Client\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Taba\Crm\Models\ActionClick;

class ActionClicksOverview extends BaseWidget
{
    protected static ?int $sort = 4;

    protected function getStats(): array
    {
        return [
            Stat::make('نقرات التواصل الكلية', ActionClick::count())
                ->description('آخر 30 يوماً: ' . ActionClick::where('created_at', '>=', now()->subDays(30))->count()),
            Stat::make('واتساب', ActionClick::where('action', 'whatsapp')->count()),
            Stat::make('اتصال', ActionClick::where('action', 'call')->count()),
            Stat::make('نموذج', ActionClick::where('action', 'form')->count()),
        ];
    }
}