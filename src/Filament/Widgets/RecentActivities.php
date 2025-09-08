<?php

namespace Taba\Crm\Filament\Widgets;

use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Spatie\Activitylog\Models\Activity;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Taba\Crm\Filament\Resources\ActivityResource;

class RecentActivities extends BaseWidget
{
    use HasWidgetShield;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Recent Activities';

    public function table(Table $table): Table
    {
        return $table
            ->query(Activity::latest()->limit(5))
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->label(__('Description')),
                Tables\Columns\TextColumn::make('causer.name')
                    ->label(__('User')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime(),
            ])
            ->actions([
                // Tables\Actions\Action::make('view')
                //     ->label(__('View'))
                //     ->url(fn (Activity $record): string => ActivityResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
