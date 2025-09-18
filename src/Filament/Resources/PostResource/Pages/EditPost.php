<?php

namespace Taba\Crm\Filament\Resources\PostResource\Pages;

use Taba\Crm\Concerns\HasPreview;
use Taba\Crm\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Forms\Get;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\TextEntry;
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
                ->label(__('Find Featured Image'))
                ->icon('heroicon-o-photo')
                ->color('primary')
                ->modalDescription(__('This will search for a free, high-quality image online using the post\'s title.'))
                ->requiresConfirmation()
                ->action(function (Post $record) { // Removed $set as we will use refreshFormData
                    try {
                        Notification::make()
                            ->title(__('Searching for Image...'))
                            ->body(__('Please wait, searching for a suitable image.'))
                            ->info()
                            ->send();

                        $searchTerm = $record->getTranslation('title', 'en');

                        if (empty($searchTerm)) {
                            Notification::make()->title(__('Search Term Missing'))->body(__('Please add a title to the post before searching for an image.'))->warning()->send();
                            return;
                        }

                        // 1. Call your ImageSearchService
                        $imageService = app(ImageSearchService::class);
                        $media = $imageService->findAndSaveImage($searchTerm, $searchTerm);

                        // --- THE FIX ---
                        // 2. Explicitly update the record in the database first.
                        $record->update(['image_id' => $media->id]);

                        // 3. Refresh the entire form's data from the now-updated record.
                        // This safely reloads all components and avoids the initialization error.
                        $this->refreshFormData(['image_id']);

                        Notification::make()
                            ->title(__('Image Found Successfully!'))
                            ->body(__('The new image has been found and set as the featured image.'))
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
                 Action::make('suggest_seo_keywords')
                ->label(__('Suggest SEO Keywords'))
                ->icon('heroicon-o-key')
                ->color('gray')
                ->action(function (Post $record) {
                    try {
                        $title = $record->getTranslation('title', 'en');
                        $content = $record->getTranslation('content', 'en');

                        if (empty($title)) {
                            Notification::make()->title(__('Content Missing'))->body(__('Please add a title before suggesting keywords.'))->warning()->send();
                            return;
                        }

                        // Create a concise summary of the content for a better prompt
                        $contentSummary = '';
                        if (is_array($content)) {
                            $firstMarkdown = Arr::first($content, fn ($block) => $block['type'] === 'markdown');
                            if ($firstMarkdown) {
                                $contentSummary = Str::limit($firstMarkdown['data']['content'], 250);
                            }
                        }

                        $textToAnalyze = "Title: {$title}\n\nContent: {$contentSummary}";

                        // Use the existing translation service to send a custom prompt
                        $translator = app(GeminiTranslationService::class);

                        // We are not really "translating", but using the AI's text generation capability.
                        // The service's `translate` method works perfectly for this.
                        $prompt = "Based on the following text, suggest 8 to 10 relevant, comma-separated SEO keywords. Provide ONLY the comma-separated list and nothing else.\n\n---\n\n{$textToAnalyze}";

                        // We trick the service by asking it to "translate" the prompt to English.
                        $keywords = $translator->translate($prompt, 'en', 'en');

                        if (!$keywords) {
                            throw new \Exception('The AI service did not return any keywords.');
                        }

                        // Open a modal to display the keywords to the user
                        Action::make('view_keywords')
                            ->infolist([
                                TextEntry::make('keywords')
                                    ->label('Suggested Keywords')
                                    ->default($keywords)
                                    ->helperText('Click the button below to copy these keywords.')
                                    ->columnSpanFull(),
                            ])
                            ->modalSubmitAction(false) // Hide the default 'Submit' button
                            ->modalCancelActionLabel('Close')
                            ->modalActions([
                                Action::make('copy')
                                    ->label('Copy to Clipboard')
                                    ->icon('heroicon-o-clipboard-document')
                                    ->color('success')
                                    ->copyable()
                                    ->copyableState(fn() => $keywords)
                                    ->close() // Close the modal after copying
                            ])
                            ->mount(); // This is a trick to immediately open the action's modal

                    } catch (\Exception $e) {
                        Notification::make()
                            ->title(__('Failed to Suggest Keywords'))
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
