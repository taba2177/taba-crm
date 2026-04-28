<?php

namespace App\Filament\Widgets;

use App\Models\ActionClick;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class ActionClicksOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected ?string $filter = '7d';

    protected function getFilters(): ?array
    {
        return [
            '7d' => 'آخر 7 أيام',
            '30d' => 'آخر 30 يوماً',
            'all' => 'الكل',
        ];
    }
    
    protected function getStats(): array
    {
        $query = ActionClick::query();

        if ($this->filter === '7d') {
            $query->where('created_at', '>=', now()->subDays(7));
        } elseif ($this->filter === '30d') {
            $query->where('created_at', '>=', now()->subDays(30));
        }

        $total = (clone $query)->count();
        $whats = (clone $query)->where('action', 'whatsapp')->count();
        $calls = (clone $query)->where('action', 'call')->count();
        $ads = (clone $query)->where('source', 'ads')->count();
        $organic = (clone $query)->where('source', 'organic')->count();

        $topCountry = (clone $query)
            ->selectRaw('country, COUNT(*) as c')
            ->whereNotNull('country')
            ->groupBy('country')
            ->orderByDesc('c')
            ->first();

        return [
            Stat::make('إجمالي النقرات', Number::format($total))
                ->description('واتساب: ' . Number::format($whats) . ' | اتصال: ' . Number::format($calls))
                ->icon('heroicon-o-bolt'),

            Stat::make('المصدر: إعلانات', Number::format($ads))
                ->description('عبر ال SEO: ' . Number::format($organic))
                ->color('warning')
                ->icon('heroicon-o-chart-bar'),

            Stat::make('أعلى دولة', $topCountry->country ?? '—')
                ->description('نقرات: ' . Number::format($topCountry->c ?? 0))
                ->icon('heroicon-o-globe-alt'),
        ];
    }
}