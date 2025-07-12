<?php

namespace Taba\Crm\Filament\Resources\PostCategoryResource\Pages;

use App\Filament\Resources\PostCategoryResource;
use App\Models\PostCategory;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;

class EditPostCategory extends EditRecord
{
    use HasPreviewModal;
    use EditRecord\Concerns\Translatable;


    protected static string $resource = PostCategoryResource::class;

    //previewmodalview

    protected function getPreviewModalView(): ?string
    {
        return 'posts.preview';
    }

    protected function getPreviewModalUrl(): ?string
    {

        return route('preview.category', [
            'category' => $this->getRecord(),
            'data' => $this->form->getState(),
        ]);
    }

    protected function getPreviewModalData(): array
    {
        return [
            'category' => $this->record,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [

            PreviewAction::make(),
            Actions\LocaleSwitcher::make()
                ->label(__('filament-locale-switcher::filament-locale-switcher.actions.locale_switcher.label'))
                ->icon('heroicon-o-globe-alt')
                ->tooltip(__('filament-locale-switcher::filament-locale-switcher.actions.locale_switcher.tooltip'))
                ->size('sm'),
            // delete
            Actions\DeleteAction::make(),
        ];
    }
}
