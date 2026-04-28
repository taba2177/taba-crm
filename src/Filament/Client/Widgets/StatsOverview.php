<?php

namespace App\Filament\Widgets;

use App\Models\advertisement;
use App\Models\offers;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\team;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $Products = Product::active()->count();
        $offers = offers::active()->count();
        $advertisements = advertisement::active()->count();
        $category = ProductCategory::active()->count();
        $categorydrafts = ProductCategory::drafts()->count();

        // Example chart data, replace with real values
        $productChartData = Product::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count')
            ->toArray();

        $categoryChartData = ProductCategory::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count')
            ->toArray();

        $offersChartData = offers::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count')
            ->toArray();

        $advertisementsChartData = advertisement::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count')
            ->toArray();

        return [
            Stat::make(__('Products'), Number::format($Products))
                ->description(__('Total number of products'))
                ->color('success')
                ->chart($productChartData)
                ->icon('heroicon-o-book-open'),

            Stat::make(__('Product Categories'), Number::format($category))
                ->color('success')
                ->description(__('Total number of product categories'))
                ->chart($categoryChartData)
                ->icon('heroicon-o-check-circle'),

            Stat::make(__('Offers'), Number::format($offers))
                ->color('success')
                ->description(__('Total number of offers'))
                ->chart($offersChartData)
                ->icon('heroicon-o-check-circle'),

            Stat::make(__('Advertisements'), Number::format($advertisements))
                ->color('success')
                ->description(__('Total number of advertisements'))
                ->chart($advertisementsChartData)
                ->icon('heroicon-o-check-circle'),
        ];
    }
}
