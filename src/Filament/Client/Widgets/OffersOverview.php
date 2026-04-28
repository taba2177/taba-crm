<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OffersResource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class OffersOverview extends BaseWidget
{

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 7;

    public function table(Table $table): Table
    {
        return $table
            ->query(OffersResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            // ->defaultSort('created_at','desc')

            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->translateLabel()
                    ->sortable(),
                Tables\Columns\TextColumn::make('percent')
                    ->numeric()
                    ->translateLabel()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('start_date')
                ->translateLabel()
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                ->translateLabel()
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]);
    }
}
