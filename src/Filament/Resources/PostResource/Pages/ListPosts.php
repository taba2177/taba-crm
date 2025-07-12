<?php

namespace Taba\Crm\Filament\Resources\PostResource\Pages;

use Taba\Crm\Filament\Resources\PostResource;
use Taba\Crm\Filament\Resources\PostResource\Widgets;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
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

    /**
     * The header widgets.
     */
    protected function getHeaderWidgets(): array
    {
        return [
            Widgets\PostOverview::class,

        ];
    }

    public function getTabs(): array
    {
        $tabs = [];

        // Get all categories from the database
        $categories = \Taba\Crm\Models\PostCategory::all();

        // Loop through the categories and create a tab for each one
        foreach ($categories as $category) {
            $tabs[$category->name] = Tab::make()
                ->label(__($category->name))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('post_category_id', $category->id));
        }

        // Add 'all' tab
        $tabs['all'] = Tab::make()
            ->label(__('All Posts'));

        return $tabs;
    }
}
