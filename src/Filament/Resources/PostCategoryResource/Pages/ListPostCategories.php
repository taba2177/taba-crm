<?php

namespace Taba\Crm\Filament\Resources\PostCategoryResource\Pages;

use App\Filament\Resources\PostCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\PostResource\Pages\HasPostPreview;

class ListPostCategories extends ListRecords
{

    use HasPostPreview;
    use ListRecords\Concerns\Translatable;
    protected static string $resource = PostCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('preview')
                ->label(__('filament.actions.preview.label'))
                ->icon('heroicon-o-eye')
                ->color('secondary')
                ->translateLabel(),
            Actions\LocaleSwitcher::make()
                ->label(__('filament-locale-switcher::filament-locale-switcher.actions.locale_switcher.label'))
                ->icon('heroicon-o-globe-alt')
                ->tooltip(__('filament-locale-switcher::filament-locale-switcher.actions.locale_switcher.tooltip'))
                ->size('sm'),
            Actions\CreateAction::make(),
        ];
    }
}