<?php

namespace Taba\Crm\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use Taba\Crm\Models\ServicePayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentAnalytics extends ChartWidget
{
    protected static ?string $heading = 'تحليل المدفوعات';

    protected int | string | array $columnSpan = 'full/2';

    protected static ?int $sort = 3; // To display it after other widgets

    protected function getData(): array
    {
        // Fetch payment data for the last 30 days
        $data = ServicePayment::query()
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as aggregate')
            )
            ->pluck('aggregate', 'date');

        return [
            'datasets' => [
                [
                    'label' => __('Revenue'),
                    'data' => $data->values()->toArray(),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                ],
            ],
            'labels' => $data->keys()->map(fn ($date) => Carbon::parse($date)->format('M d'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}