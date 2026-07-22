<?php

namespace Taba\Crm\Filament\Admin\Widgets;

use Taba\Crm\Filament\Admin\Resources\PostResource;
use Taba\Crm\Models\Post;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Actions;

class LatestPosts extends BaseWidget
{
    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 5;

    protected static ?string $heading = null;

    public function getHeading(): ?string
    {
        return __('Latest Posts');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(PostResource::getEloquentQuery()->latest()->limit(5))
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title')),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('Author'))
                    ->default('-'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime(),
            ])
            ->actions([
                Actions\Action::make('edit')
                    ->label(__('Edit'))
                    ->url(fn (Post $record): string => PostResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
