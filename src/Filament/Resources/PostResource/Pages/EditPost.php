<?php

namespace Taba\Crm\Filament\Resources\PostResource\Pages;

use Taba\Crm\Concerns\HasPreview;
use Taba\Crm\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;

class EditPost extends EditRecord
{
    use HasPreview;

    use EditRecord\Concerns\Translatable;

    /**
     * The resource model.
     */
    protected static string $resource = PostResource::class;

    /**
     * The header actions.
     * We have moved the navigation actions back here to ensure they are visible.
     */
    protected function getHeaderActions(): array
    {
        return [
            PreviewAction::make(),

            Actions\LocaleSwitcher::make()
                ->label(__('filament-locale-switcher::filament-locale-switcher.actions.locale_switcher.label'))
                ->icon('heroicon-o-globe-alt')
                ->tooltip(__('filament-locale-switcher::filament-locale-switcher.actions.locale_switcher.tooltip'))
                ->size('sm'),

            // The navigation actions are now merged back into the header actions array.
            // ...$this->getNavigationActions(),
        ];
    }

    /**
     * Adds Next and Previous navigation actions with post names.
     */
    // protected function getNavigationActions(): array
    // {
    //     // We will build the actions array conditionally.
    //     $actions = [];

    //     $recordId = $this->record->id;

    //     // Find the previous post.
    //     // We order by ID in descending order to find the closest one.
    //     $previous = static::getResource()::getEloquentQuery()
    //         ->where('id', '<', $recordId)
    //         ->orderBy('id', 'desc')
    //         ->first();

    //     // Only add the action if a previous post exists.
    //     if ($previous) {
    //         $actions[] = Actions\Action::make('previous')
    //             // Use the post's title for the label.
    //             ->label($previous->title)
    //             ->url(fn (): string => static::getResource()::getUrl('edit', ['record' => $previous]))
    //             ->icon('heroicon-o-arrow-right')
    //             ->color('gray') // Changes the button color to green
    //             ->keyBindings(['arrow-right']);
    //     }

    //     // Find the next post.
    //     // We order by ID in ascending order to find the closest one.
    //     $next = static::getResource()::getEloquentQuery()
    //         ->where('id', '>', $recordId)
    //         ->orderBy('id', 'asc')
    //         ->first();

    //     // Only add the action if a next post exists.
    //     if ($next) {
    //         // Apply a margin-left of 'auto' to push this button to the far right.
    //         $actions[] = Actions\Action::make('next')
    //             // Use the post's title for the label.
    //             ->label($next->title)
    //             ->url(fn (): string => static::getResource()::getUrl('edit', ['record' => $next]))
    //             // Place the icon on the right for "next" actions.
    //             ->icon('heroicon-o-arrow-left')
    //             ->iconPosition('after')
    //             ->color('gray') // Changes the button color to green
    //             ->keyBindings(['arrow-left']);
    //     }

    //     return $actions;
    // }
}
