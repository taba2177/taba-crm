<?php
// src/Filament/Client/Widgets/WeeklyReviewsChart.php

namespace Taba\Crm\Filament\Client\Widgets;

use Filament\Widgets\ChartWidget;
use Taba\Crm\Models\ContactEntry;

class WeeklyReviewsChart extends ChartWidget
{
    protected static ?string $heading = 'الرسائل الأسبوعية';
    protected static ?int $sort = 9;

    protected function getData(): array
    {
        $days = collect(range(6, 0))->map(fn($i) => now()->subDays($i)->format('Y-m-d'));

        $counts = ContactEntry::selectRaw('DATE(created_at) as date, count(*) as total')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->pluck('total', 'date');

        return [
            'datasets' => [[
                'label' => 'رسائل',
                'data'  => $days->map(fn($d) => $counts[$d] ?? 0)->values()->toArray(),
            ]],
            'labels' => $days->values()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}