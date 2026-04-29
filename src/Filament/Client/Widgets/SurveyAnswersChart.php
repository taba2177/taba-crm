<?php
// src/Filament/Client/Widgets/SurveyAnswersChart.php

namespace Taba\Crm\Filament\Client\Widgets;

use Filament\Widgets\ChartWidget;
use Taba\Crm\Models\ContactEntry;

class SurveyAnswersChart extends ChartWidget
{
    protected static ?string $heading = 'الرسائل حسب الصفحة';
    protected static ?int $sort = 8;

    protected function getData(): array
    {
        $rows = ContactEntry::selectRaw('page, count(*) as total')
            ->whereNotNull('page')
            ->groupBy('page')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        return [
            'datasets' => [[
                'data'            => $rows->pluck('total')->toArray(),
                'backgroundColor' => ['#6366f1','#22c55e','#f59e0b','#ef4444','#3b82f6','#ec4899','#14b8a6','#a855f7'],
            ]],
            'labels' => $rows->pluck('page')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}