<?php

namespace Taba\Crm\Filament\Admin\Widgets;

use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Taba\Crm\Models\User;

class VisitorAnalytics extends ChartWidget
{
    use HasWidgetShield;

    protected static bool $isLazy = false;

    protected ?string $heading = null;

    protected int | string | array $columnSpan = 'full/2';

    protected static ?int $sort = 3;

    public function getHeading(): ?string
    {
        return __('New User Registrations');
    }

    protected function getData(): array
    {
        $startDate = Carbon::now()->subDays(7);

        $data = User::query()
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->pluck('count', 'date');

        return [
            'datasets' => [
                [
                    'label' => __('New Users'),
                    'data' => $data->values()->toArray(),
                    'backgroundColor' => 'rgba(129, 223, 67, 0.5)',
                    'borderColor' => 'rgba(129, 223, 67, 1)',
                ],
            ],
            'labels' => $data->keys()->map(fn ($date) => Carbon::parse($date)->format('M d'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
