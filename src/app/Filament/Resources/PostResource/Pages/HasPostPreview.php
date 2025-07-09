<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Fields\PageContent;
use Filament\Forms\Components\Component;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;
use Pboivin\FilamentPeek\Pages\Concerns\HasBuilderPreview;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;

trait HasPostPreview
{
    use HasPreviewModal;
    // use HasBuilderPreview;

    protected function getPreviewModalUrl(): ?string
    {
        return route('preview.post', [
            'post' => $this->getRecord(),
            'data' => $this->form->getState(),
        ]);
    }
    protected function getActions(): array
    {
        return [
            PreviewAction::make()->label('Preview Changes'),
        ];
    }
    protected function getPreviewModalView(): ?string
    {
        return 'page.preview-content';
    }

    protected function getPreviewModalDataRecordKey(): ?string
    {
        return 'page';
    }

    protected function getBuilderPreviewView(string $builderName): ?string
    {
        return 'page.preview-content';
    }

    // public static function getBuilderEditorSchema(string $builderName): Component|array
    // {
    //     return [
    //         PageContent::make(
    //             name: 'content',
    //             context: 'preview',
    //         ),
    //     ];
    // }
}