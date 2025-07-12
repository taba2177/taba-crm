<?php

namespace Taba\Crm\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    use HasPostPreview;
    use CreateRecord\Concerns\Translatable;
    /**
     * The resource model.
     */
    protected static string $resource = PostResource::class;

    // gethederactions
    protected function getHeaderActions(): array
    {
        return [
            // Actions\LocaleSwitcher::make()
            //     ->label(__('filament-locale-switcher::filament-locale-switcher.actions.locale_switcher.label'))
            //     ->icon('heroicon-o-globe-alt')
            //     ->tooltip(__('filament-locale-switcher::filament-locale-switcher.actions.locale_switcher.tooltip'))
            //     ->size('sm'),
        ];
    }

}
