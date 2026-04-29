<?php
// src/Filament/Client/Widgets/OffersOverview.php

namespace Taba\Crm\Filament\Client\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Taba\Crm\Models\ContactEntry;

class OffersOverview extends BaseWidget
{
    protected ?string $heading = 'الرسائل الواردة';
    protected static ?int $sort = 7;

    protected function getStats(): array
    {
        return [
            Stat::make('إجمالي الرسائل', ContactEntry::count()),
            Stat::make('غير مقروءة', ContactEntry::where('is_read', false)->count()),
            Stat::make('هذا الأسبوع', ContactEntry::where('created_at', '>=', now()->startOfWeek())->count()),
        ];
    }
}