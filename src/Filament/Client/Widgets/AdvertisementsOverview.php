<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\AdvertisementResource;
use App\Filament\Resources\ProductResource;
use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class AdvertisementsOverview extends BaseWidget
{
        protected static ?int $sort = 5;

        protected int | string | array $columnSpan = 'full';


    public function table(Table $table): Table
    {
        return $table
  ->query(ProductResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at','desc')

            ->columns([
                  Tables\Columns\TextColumn::make('name')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
                // Tables\Columns\TextColumn::make('url')
                //     ->translateLabel()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('price')
                //     ->translateLabel()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('offer_price')
                    ->translateLabel()
                    ->sortable()
                    ->hidden(fn (Product $model) => !$model->isOnOffer()), // Hide if no offer
                Tables\Columns\ImageColumn::make('image')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('product_category.category_name')
                    ->translateLabel()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->translateLabel()
                    ->boolean()
                    ->sortable(),
            ]);
    }
}
