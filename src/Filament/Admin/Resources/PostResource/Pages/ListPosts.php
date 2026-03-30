<?php

namespace Taba\Crm\Filament\Admin\Resources\PostResource\Pages;

use Taba\Crm\Filament\Admin\Resources\PostResource;
use Taba\Crm\Filament\Admin\Resources;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListPosts extends ListRecords
{
    use HasPostPreview;

    use ListRecords\Concerns\Translatable;
    /**
     * The resource model.
     */
    protected static string $resource = PostResource::class;

    /**
     * The header actions.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\LocaleSwitcher::make()
                ->label(__('filament-locale-switcher::filament-locale-switcher.actions.locale_switcher.label'))
                ->icon('heroicon-o-globe-alt')
                ->tooltip(__('filament-locale-switcher::filament-locale-switcher.actions.locale_switcher.tooltip'))
                ->size('sm'),
        ];
    }

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getTableQuery();

        if ($categoryId = request()->query('category')) {
            $query->where('post_category_id', $categoryId);
        }

        return $query;
    }
    /**
     * The header widgets.
     */
    // protected function getHeaderWidgets(): array
    // {
    //     return [
    //         PostOverview::class,
    //     ];
    // }

}
