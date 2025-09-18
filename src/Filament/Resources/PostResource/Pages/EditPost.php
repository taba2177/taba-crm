<?php

namespace Taba\Crm\Filament\Resources\PostResource\Pages;

use Taba\Crm\Concerns\HasPreview;
use Taba\Crm\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Arr;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;
use Taba\Crm\Services\GeminiImageGenerationService;
use Taba\Crm\Services\GeminiTranslationService;
use Illuminate\Support\Str;
use Taba\Crm\Models\Post;
use Taba\Crm\Services\ImageSearchService;

class EditPost extends EditRecord
{
    use HasPreviewModal;

    use EditRecord\Concerns\Translatable;

    /**
     * The resource model.
     */
    protected static string $resource = PostResource::class;


    protected function getPreviewModalView(): ?string
    {
        return 'posts.preview';
    }

    protected function getPreviewModalUrl(): ?string
    {
        return route('preview.post', [
            'post' => $this->getRecord(),
            'data' => $this->form->getState(),
        ]);
    }

    protected function getPreviewModalData(): array
    {
        return [
            'post' => $this->record,
        ];
    }


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

          Actions\Action::make('find_featured_image')
            ->label(__('Find Featured Image')) // Changed label
            ->icon('heroicon-o-photo') // Changed icon to be more appropriate
            ->color('primary')
            ->visible(fn () => auth()->user()->hasRole('super_admin'))
            ->modalDescription(__('This will search for a free, high-quality image online using the post\'s title.')) // Updated description
            ->requiresConfirmation()
            ->action(function (\Filament\Forms\Set $set, Post $record) {
                try {
                    // Notify the user that the process has started
                    Notification::make()
                        ->title(__('Searching for Image...'))
                        ->body(__('Please wait, searching for a suitable image.'))
                        ->info()
                        ->send();

                    // Use a specific language for the search term for better results
                    $searchTerm = $record->getTranslation('title', 'en');

                    if (empty($searchTerm)) {
                        Notification::make()->title(__('Search Term Missing'))->body(__('Please add a title to the post before searching for an image.'))->warning()->send();
                        return;
                    }

                    // 1. Call your new ImageSearchService
                    $imageService = app(ImageSearchService::class);

                    // 2. Use the findAndSaveImage method with the title as the search term
                    $media = $imageService->findAndSaveImage($searchTerm, $searchTerm);

                    // 3. Update the 'image_id' field in the form with the new image's ID
                    $set('image_id', $media->id);

                    Notification::make()
                        ->title(__('Image Found Successfully!'))
                        ->body(__('A new image has been found and set as the featured image.'))
                        ->success()
                        ->send();

                } catch (\Exception $e) {
                     Notification::make()
                        ->title(__('Image Search Failed'))
                        ->body($e->getMessage())
                        ->danger()
                        ->send();
                    report($e);
                }
            }),

                Actions\Action::make('auto_translate')
                ->label(__('Auto-Translate All Fields'))
                ->icon('heroicon-o-language')
                ->color('gray')
                ->visible(fn () => auth()->user()->hasRole('super_admin'))
                ->action(function () {
                    try {
                        $record = $this->getRecord();
                        $translator = app(GeminiTranslationService::class);

                        // Determine source and target languages based on the active tab
                        $sourceLocale = $this->activeLocale;
                        $targetLocale = $sourceLocale === 'en' ? 'ar' : 'en';

                        // 1. Prepare all translatable texts in a single batch
                        $textsToTranslate = [];
                        $translatableAttributes = $record->getTranslatableAttributes();

                        foreach ($translatableAttributes as $attribute) {
                            $value = $record->getTranslation($attribute, $sourceLocale, false);

                            if (empty($value)) continue;

                            if ($attribute === 'content' && is_array($value)) {
                                foreach ($value as $index => $block) {
                                    if ($block['type'] === 'markdown' && !empty($block['data']['content'])) {
                                        // Add each markdown block to the batch with a unique key
                                        $textsToTranslate["content_{$index}"] = $block['data']['content'];
                                    }
                                }
                            } elseif ($attribute === 'metadata' && is_array($value)) {
                                foreach ($value as $key => $metaValue) {
                                     // Add each metadata value to the batch with a unique key
                                    if (!empty($metaValue)) {
                                        $textsToTranslate["metadata_{$key}"] = $metaValue;
                                    }
                                }
                            } else if (is_string($value)) {
                                $textsToTranslate[$attribute] = $value;
                            }
                        }

                        if (empty($textsToTranslate)) {
                            Notification::make()->title(__('Nothing to translate'))->body(__('All source fields are empty.'))->warning()->send();
                            return;
                        }

                        // 2. Call the AI service once with the entire batch
                        $translatedTexts = $translator->translateMany($textsToTranslate, $sourceLocale, $targetLocale);

                        if (empty($translatedTexts)) {
                             throw new \Exception('The translation service returned an empty result.');
                        }

                        // 3. Update the record with the translated texts
                        foreach ($translatableAttributes as $attribute) {
                             if ($attribute === 'content' && is_array($record->content)) {
                                $newContent = $record->getTranslation('content', $sourceLocale, false);
                                foreach ($newContent as $index => &$block) {
                                    if ($block['type'] === 'markdown' && isset($translatedTexts["content_{$index}"])) {
                                        $block['data']['content'] = $translatedTexts["content_{$index}"];
                                    }
                                }
                                $record->setTranslation('content', $targetLocale, $newContent);

                            } elseif ($attribute === 'metadata' && is_array($record->metadata)) {
                                $newMetadata = $record->getTranslation('metadata', $sourceLocale, false);
                                foreach ($newMetadata as $key => &$metaValue) {
                                     if (isset($translatedTexts["metadata_{$key}"])) {
                                        $newMetadata[$key] = $translatedTexts["metadata_{$key}"];
                                    }
                                }
                                $record->setTranslation('metadata', $targetLocale, $newMetadata);
                            }
                            else if (isset($translatedTexts[$attribute])) {
                                $record->setTranslation($attribute, $targetLocale, $translatedTexts[$attribute]);
                            }
                        }

                        $record->save();

                        // Refresh the form data to show the new translations
                        $this->refreshFormData($translatableAttributes);

                        Notification::make()
                            ->title(__('Translation Successful'))
                            ->body(__('All fields have been translated to') . " " . strtoupper($targetLocale))
                            ->success()
                            ->send();

                    } catch (\Exception $e) {
                        Notification::make()
                            ->title(__('Translation Error'))
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                        report($e);
                    }
                }),
        ];


    }


}
