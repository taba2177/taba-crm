<?php

namespace Taba\Crm\Filament\Admin\Widgets;

use Taba\Crm\Filament\Admin\Resources\PostResource;
use Taba\Crm\Models\Post;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestPosts extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(PostResource::getEloquentQuery()->latest()->limit(5))
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title')),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('Author')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime(),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label(__('Edit'))
                    ->url(fn (Post $record): string => PostResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
