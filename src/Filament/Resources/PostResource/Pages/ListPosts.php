<?php

namespace Taba\Crm\Filament\Resources\PostResource\Pages;

use Taba\Crm\Filament\Resources\PostResource;
use Taba\Crm\Filament\Resources;
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

// show posts names as tabs and show edit abillty postedit page under them
    // public function getTabs(): array
    // {
    //     $tabs = [
    //         'all' => Tab::make()
    //             ->label(__('All Posts')),
    //     ];

    //     $posts = \Taba\Crm\Models\Post::all();

    //     foreach ($posts as $post) {
    //         $tabs[$post->name] = Tab::make()
    //             ->label($post->name)
    //             ->modifyQueryUsing(fn (Builder $query) => $query->where('id', $post->id));
    //     }

    //     return $tabs;
    // }
}
