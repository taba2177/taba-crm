<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Concerns\HasPreview;
use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;

class EditPost extends EditRecord
{
    use HasPostPreview;

    use EditRecord\Concerns\Translatable;

    /**
     * The resource model.
     */
    protected static string $resource = PostResource::class;

    /**
     * The preview modal URL.
     */
    // protected function getPreviewModalUrl(): ?string
    // {
    //     $this->generatePreviewSession();

    //     //if post_category page or post
    //     switch ($this->record->postCategory->slug) {
    //         case 'page':
    //             return route('page', [
    //                 'post' => $this->record->slug,
    //                 'previewToken' => $this->previewToken,
    //             ]);
    //         default:
    //             return route('posts.show', [
    //                 'post' => $this->record->slug,
    //                 'previewToken' => $this->previewToken,
    //             ]);
    //     }
    // }

    /**
     * The header actions.
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
        ];
    }
    
}