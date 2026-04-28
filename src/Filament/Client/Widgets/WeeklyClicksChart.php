<?php

namespace App\Filament\Widgets;

use App\Models\ActionClick;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;

class WeeklyClicksChart extends ChartWidget
{
    protected ?string $heading = 'النقرات خلال الأسبوع';

    protected static ?int $sort = 2;

    public ?string $chartType = 'bar';
    public ?string $actionFilter = 'all';
    public ?string $dateRange = 'last7days';
    public ?string $customStart = null;
    public ?string $customEnd = null;

    protected function getFilters(): ?array
    {
        return [
            '7d' => 'آخر 7 أيام',
            '30d' => 'آخر 30 يوماً',
            'all' => 'الكل',
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('chartType')
                ->label('نوع الرسم البياني')
                ->options([
                    'bar' => 'أعمدة',
                    'line' => 'خط',
                    'doughnut' => 'دائري',
                    'radar' => 'رادار',
                ])
                ->default($this->chartType)
                ->reactive(),

            Select::make('actionFilter')
                ->label('نوع الإجراء')
                ->options([
                    'all' => 'الكل',
                    'whatsapp' => 'واتساب',
                    'call' => 'اتصال',
                ])
                ->default($this->actionFilter)
                ->reactive(),

            Select::make('dateRange')
                ->label('الفترة الزمنية')
                ->options([
                    'last7days' => 'آخر 7 أيام',
                    'last30days' => 'آخر 30 يومًا',
                    'thisMonth' => 'هذا الشهر',
                    'lastMonth' => 'الشهر الماضي',
                    'custom' => 'مخصص',
                ])
                ->default($this->dateRange)
                ->reactive(),

            DatePicker::make('customStart')
                ->label('من تاريخ')
                ->displayFormat('Y-m-d')
                ->visible(fn ($get) => $get('dateRange') === 'custom')
                ->reactive(),

            DatePicker::make('customEnd')
                ->label('إلى تاريخ')
                ->displayFormat('Y-m-d')
                ->visible(fn ($get) => $get('dateRange') === 'custom')
                ->reactive(),
        ];
    }

    protected function getType(): string
    {
        return $this->chartType ?? 'bar';
    }

    protected function getData(): array
    {
        $start = Carbon::today()->subDays(6);
        $end = Carbon::today();

        switch ($this->dateRange) {
            case 'last30days':
                $start = Carbon::today()->subDays(29);
                break;
            case 'thisMonth':
                $start = Carbon::today()->startOfMonth();
                $end = Carbon::today()->endOfMonth();
                break;
            case 'lastMonth':
                $start = Carbon::today()->subMonth()->startOfMonth();
                $end = Carbon::today()->subMonth()->endOfMonth();
                break;
            case 'custom':
                if ($this->customStart) $start = Carbon::parse($this->customStart);
                if ($this->customEnd) $end = Carbon::parse($this->customEnd);
                break;
        }

        $days = [];
        $labels = [];
        $dayNames = [
            0 => 'الأحد',
            1 => 'الاثنين',
            2 => 'الثلاثاء',
            3 => 'الأربعاء',
            4 => 'الخميس',
            5 => 'الجمعة',
            6 => 'السبت',
        ];

        $period = Carbon::parse($start)->daysUntil($end);
        foreach ($period as $date) {
            $key = $date->toDateString();
            $days[$key] = [
                'whatsapp' => 0,
                'call' => 0,
                'total' => 0,
            ];
            $labels[] = $dayNames[$date->dayOfWeek] . ' ' . $date->format('d/m');
        }

        $query = ActionClick::query()
            ->selectRaw('DATE(created_at) as day, action, COUNT(*) as total')
            ->whereDate('created_at', '>=', $start->toDateString())
            ->whereDate('created_at', '<=', $end->toDateString());

        if ($this->actionFilter !== 'all') {
            $query->where('action', $this->actionFilter);
        }

        $rows = $query
            ->groupBy('day', 'action')
            ->orderBy('day')
            ->get();

        foreach ($rows as $row) {
            $day = $row->day;
            $action = $row->action;
            $count = (int) $row->total;
            if (isset($days[$day])) {
                if (isset($days[$day][$action])) {
                    $days[$day][$action] = $count;
                }
                $days[$day]['total'] += $count;
            }
        }

        $whatsappCounts = [];
        $callCounts = [];
        $totalCounts = [];

        foreach ($days as $info) {
            $whatsappCounts[] = $info['whatsapp'];
            $callCounts[] = $info['call'];
            $totalCounts[] = $info['total'];
        }

        $datasets = [];
        if ($this->actionFilter === 'all' || $this->actionFilter === 'whatsapp') {
            $datasets[] = [
                'label' => 'واتساب',
                'data' => $whatsappCounts,
                'backgroundColor' => '#22c55e',
                'borderRadius' => 6,
                'borderSkipped' => false,
            ];
        }
        if ($this->actionFilter === 'all' || $this->actionFilter === 'call') {
            $datasets[] = [
                'label' => 'اتصال',
                'data' => $callCounts,
                'backgroundColor' => '#06b6d4',
                'borderRadius' => 6,
                'borderSkipped' => false,
            ];
        }
        if ($this->actionFilter === 'all') {
            $datasets[] = [
                'label' => 'إجمالي',
                'data' => $totalCounts,
                'backgroundColor' => '#fde047',
                'borderRadius' => 6,
                'borderSkipped' => false,
            ];
        }

        return [
            'labels' => $labels,
            'datasets' => $datasets,
        ];
    }
}