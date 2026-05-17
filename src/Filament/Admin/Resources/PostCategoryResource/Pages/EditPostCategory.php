<?php

namespace Taba\Crm\Filament\Admin\Resources\PostCategoryResource\Pages;

use Taba\Crm\Jobs\GenerateCategoryPreview;
use Taba\Crm\Filament\Admin\Resources\PostCategoryResource;
use Taba\Crm\Models\PostCategory;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;
use Spatie\Browsershot\Browsershot;


class EditPostCategory extends EditRecord
{
    use HasPreviewModal;


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

            Actions\Action::make('generate_preview')
                ->label('Generate Preview Image')
                ->icon('heroicon-o-camera')
                //just visibale if super admin
                ->visible(fn () => auth()->user()->hasRole('super_admin'))
                ->action(function () {
                    // Dispatch the job with the current model and form data
                    // dd($record, $data);
                    // dd($this->getRecord(), $this->form->getState());
                    GenerateCategoryPreview::dispatch($this->getRecord(), $this->form->getState());

                    // Give the user feedback
                    Notification::make()
                        ->title('Preview generation started')
                        ->body('The preview image is being generated in the background.')
                        ->success()
                        ->send();
                }),
              Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Delete category')
                ->modalDescription('Please choose how to handle posts linked to this category.')

                // This adds the checkbox to the confirmation modal.
                ->form([
                    \Filament\Forms\Components\Checkbox::make('delete_posts')
                        ->label('Also delete all posts in this category.')
                        ->helperText('If unchecked, posts will be kept but will no longer have a category.'),
                ])

                ->action(function ($record, array $data) {
                    if ($data['delete_posts']) {
                        $record->posts()->delete();
                    } else {
                        $record->posts()->update(['post_category_id' => null]);
                    }

                    $record->delete();
                }),
        ];
    }
}
