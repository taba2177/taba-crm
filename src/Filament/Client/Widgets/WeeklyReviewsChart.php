<?php

namespace App\Filament\Widgets;

use App\Models\LocalReview;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Support\Facades\DB;

class WeeklyReviewsChart extends ChartWidget
{
    protected ?string $heading = 'المراجعات الأسبوعية';

    protected static ?int $sort = 2;

    // protected int | string | array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $labels = [];
        $avgScores = [];
        $surveyCounts = [];

        $start = Carbon::now()->startOfWeek();

        for ($i = 0; $i < 7; $i++) {
            $day = (clone $start)->addDays($i);
            $labels[] = $day->translatedFormat('D');

            $scores = LocalReview::query()
                ->whereDate('created_at', $day->toDateString())
                ->pluck('survey_score');

            $count = $scores->count();
            $sum = $scores->sum();
            $avg = $count > 0 ? round($sum / $count, 2) : 0;

            $avgScores[] = $avg;
            $surveyCounts[] = $count;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'متوسط التقييم',
                    'data' => $avgScores,
                    'borderColor' => '#1f8ef1',
                    'backgroundColor' => 'rgba(31,142,241,0.2)',
                    'fill' => false,
                    'tension' => 0.3,
                    'yAxisID' => 'y-score',
                ],
                [
                    'label' => 'عدد الاستبيانات',
                    'data' => $surveyCounts,
                    'borderColor' => '#f39c12',
                    'backgroundColor' => 'rgba(243,156,18,0.2)',
                    'fill' => false,
                    'tension' => 0.3,
                    'yAxisID' => 'y-count',
                ],
            ],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
            'scales' => [
                'y-score' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'التقييم',
                    ],
                ],
                'y-count' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'عدد الاستبيانات',
                    ],
                ],
            ],
        ];
    }
}