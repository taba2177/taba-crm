<?php
// src/Filament/Client/Widgets/WeeklyClicksChart.php

namespace Taba\Crm\Filament\Client\Widgets;

use Filament\Widgets\ChartWidget;
use Taba\Crm\Models\ActionClick;
use Illuminate\Support\Carbon;

class WeeklyClicksChart extends ChartWidget
{
    protected static ?string $heading = 'النقرات حسب اليوم';
    protected static ?int $sort = 5;

    protected function getData(): array
    {
        $days = collect(range(6, 0))->map(fn($i) => now()->subDays($i)->format('Y-m-d'));

        $clicks = ActionClick::selectRaw('DATE(created_at) as date, count(*) as total')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->pluck('total', 'date');

        return [
            'datasets' => [[
                'label' => 'النقرات',
                'data'  => $days->map(fn($d) => $clicks[$d] ?? 0)->values()->toArray(),
            ]],
            'labels' => $days->values()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}