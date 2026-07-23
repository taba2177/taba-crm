<?php

namespace Taba\Crm\Filament\Admin\Widgets;

use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Spatie\Activitylog\Models\Activity;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Actions;
use Taba\Crm\Filament\Admin\Resources\ActivityResource;

class RecentActivities extends BaseWidget
{
    use HasWidgetShield;

    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = null;

    protected static ?int $sort = 6;

    public static function canView(): bool
    {
        try {
            return Activity::exists();
        } catch (\Throwable) {
            return false;
        }
    }

    public function getHeading(): ?string
    {
        return __('Recent Activities');
    }

    public function table(Table $table): Table
    {
        return $table
            // Select only the columns the table renders. Never load `properties`
            // here: it can be very large and would exhaust memory when hydrated.
            ->query(
                Activity::query()
                    ->select(['id', 'log_name', 'description', 'subject_type', 'subject_id', 'causer_type', 'causer_id', 'created_at'])
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->label(__('Description')),
                Tables\Columns\TextColumn::make('causer.name')
                    ->label(__('User'))
                    ->default('-'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime(),
            ])
            ->actions([
                // Actions\Action::make('view')
                //     ->label(__('View'))
                //     ->url(fn (Activity $record): string => ActivityResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
