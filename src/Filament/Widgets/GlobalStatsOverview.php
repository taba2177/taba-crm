<?php

namespace Taba\Crm\Filament\Widgets;

use Taba\Crm\Models\ContactEntry;
use Taba\Crm\Models\Post;
use Taba\Crm\Models\PostCategory;
use Taba\Crm\Models\ServicePayment;
use Taba\Crm\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Number;

class GlobalStatsOverview extends BaseWidget
{
    // protected function getHeading(): ?string
    // {
    //     return 'العنوان';
    // }

    // protected function getDescription(): ?string
    // {
    //     return 'نظرة عامة على بعض التحليلات.';
    // }
  /**
     * Helper function to calculate percentage change between two periods.
     *
     * @param int $currentPeriodCount
     * @param int $previousPeriodCount
     * @return float
     */
    private function calculatePercentageChange(int $currentPeriodCount, int $previousPeriodCount): float
    {
        if ($previousPeriodCount == 0) {
            // Avoid division by zero. If previous was 0, any increase is technically infinite.
            // We'll show 100% increase if the current count is positive.
            return $currentPeriodCount > 0 ? 100.0 : 0.0;
        }

        return (($currentPeriodCount - $previousPeriodCount) / $previousPeriodCount) * 100;
    }

    /**
     * Helper function to generate chart data for the last 7 days for a given model.
     *
     * @param string $modelClass
     * @param Carbon $startDate
     * @return array
     */
    private function getChartData(string $modelClass, Carbon $startDate): array
    {
        return $modelClass::query()
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->pluck('count')
            ->toArray();
    }


    protected function getStats(): array
    {
        $user = auth()->user();
        $isClient = $user->hasRole('client');

        $endDate = Carbon::now();
        $startDateCurrent = $endDate->copy()->subDays(7);
        $startDatePrevious = $startDateCurrent->copy()->subDays(7);

        if ($isClient) {
            // Client sees only their own stats
            $myPublished = Post::where('user_id', $user->id)->where('is_published', true)->count();
            $myDrafts = Post::where('user_id', $user->id)->where('is_published', false)->count();
            $myPayments = ServicePayment::where('user_id', $user->id)->sum('amount');

            return [
                Stat::make(__('My Published Posts'), Number::format($myPublished))
                    ->description(__('Your live posts'))
                    ->color('success')
                    ->icon('heroicon-o-newspaper'),

                Stat::make(__('My Drafts'), Number::format($myDrafts))
                    ->description(__('Posts pending publication'))
                    ->color('warning')
                    ->icon('heroicon-o-pencil-square'),

                Stat::make(__('My Payments'), __('SAR') . ' ' . Number::format($myPayments, 2))
                    ->description(__('Total from your payments'))
                    ->color('primary')
                    ->icon('heroicon-o-banknotes'),
            ];
        }

        // Admin/super_admin sees everything
        $currentUserCount = User::whereBetween('created_at', [$startDateCurrent, $endDate])->count();
        $previousUserCount = User::whereBetween('created_at', [$startDatePrevious, $startDateCurrent])->count();
        $userChange = $this->calculatePercentageChange($currentUserCount, $previousUserCount);

        $currentEntries = ContactEntry::whereBetween('created_at', [$startDateCurrent, $endDate])->count();
        $previousEntries = ContactEntry::whereBetween('created_at', [$startDatePrevious, $startDateCurrent])->count();
        $entryChange = $this->calculatePercentageChange($currentEntries, $previousEntries);

        return [
            Stat::make(__('Total Users'), Number::format(User::count()))
                ->description(sprintf('%d%% %s', abs($userChange), $userChange >= 0 ? __('increase') : __('decrease')))
                ->descriptionIcon($userChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($userChange >= 0 ? 'success' : 'danger')
                ->chart($this->getChartData(User::class, $startDateCurrent))
                ->icon('heroicon-o-users'),

            Stat::make(__('Published Posts'), Number::format(Post::where('is_published', true)->count()))
                ->description(__('Total live posts'))
                ->color('success')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->icon('heroicon-o-newspaper'),

            Stat::make(__('Draft Posts'), Number::format(Post::where('is_published', false)->count()))
                ->description(__('Posts pending publication'))
                ->color('warning')
                ->icon('heroicon-o-pencil-square'),

            Stat::make(__('Post Categories'), Number::format(PostCategory::count()))
                ->description(__('Total number of categories'))
                ->color('info')
                ->icon('heroicon-o-tag'),

            Stat::make(__('Contact Form Entries'), Number::format(ContactEntry::count()))
                ->description(sprintf('%d%% %s', abs($entryChange), $entryChange >= 0 ? __('increase') : __('decrease')))
                ->descriptionIcon($entryChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($entryChange >= 0 ? 'success' : 'danger')
                ->chart($this->getChartData(ContactEntry::class, $startDateCurrent))
                ->icon('heroicon-o-inbox-stack'),
        ];
    }
}