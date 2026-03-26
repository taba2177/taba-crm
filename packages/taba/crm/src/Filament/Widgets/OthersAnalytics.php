<?php

namespace Taba\Crm\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Taba\Crm\Models\ServicePayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OthersAnalytics extends ChartWidget
{
    protected static ?string $heading = 'تحليل المدفوعات';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 3; // To display it after other widgets

    protected function getData(): array
    {
        // Fetch payment data for the last 30 days, getting both sum and count in one query
        $data = ServicePayment::query()
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as total_revenue'),
                DB::raw('COUNT(*) as payments_count')
            )
            ->get();

        return [
            'datasets' => [
                [
                    'label' => __('Revenue'),
                    'data' => $data->pluck('total_revenue')->toArray(),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'yAxisID' => 'revenue', // Assign to the first Y-axis
                    'type' => 'bar',
                ],
                [
                    'label' => __('Payments Count'),
                    'data' => $data->pluck('payments_count')->toArray(),
                    'backgroundColor' => 'rgba(255, 99, 132, 0.5)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'yAxisID' => 'count', // Assign to the second Y-axis
                    'type' => 'line',
                ],
            ],
            'labels' => $data->pluck('date')->map(fn ($date) => Carbon::parse($date)->format('M d'))->toArray(),
        ];
    }

    // protected function getOptions(): array
    // {
    //     return [
    //         'scales' => [
    //             'y' => [
    //                 'revenue' => [
    //                     'type' => 'linear',
    //                     'display' => true,
    //                     'position' => 'left',
    //                     'title' => [
    //                         'display' => true,
    //                         'text' => __('Revenue'),
    //                     ],
    //                 ],
    //                 'count' => [
    //                     'type' => 'linear',
    //                     'display' => true,
    //                     'position' => 'right',
    //                     'title' => [
    //                         'display' => true,
    //                         'text' => __('Payments Count'),
    //                     ],
    //                     'grid' => [
    //                         'drawOnChartArea' => false, // Only show grid lines for the first axis
    //                     ],
    //                 ],
    //             ]
    //         ],
    //     ];
    // }


    protected function getType(): string
    {
        // The type is now defined per dataset to allow mixing bars and lines
        return 'bar';
    }
}