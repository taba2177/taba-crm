<?php

namespace Taba\Crm\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class VisitorAnalytics extends ChartWidget
{
    protected static ?string $heading = 'تحليل الزوار';

    protected int | string | array $columnSpan = 'full/2';

    protected function getData(): array
    {
        // This is sample data.
        // TODO: Replace this with your actual analytics data source.
        return [
            'datasets' => [
                [
                    'label' => 'Visitors',
                    'data' => [210, 350, 420, 380, 550, 780, 990],
                    'backgroundColor' => 'rgba(129, 223, 67, 0.5)',
                    'borderColor' => 'rgba(219, 198, 103, 1)',
                ],
            ],
            'labels' => ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Day 7'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
