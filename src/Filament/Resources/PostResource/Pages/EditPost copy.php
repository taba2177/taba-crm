<?php

namespace Taba\Crm\Filament\Resources\PostResource\Pages;

use Taba\Crm\Filament\Resources\PostResource;
use Taba\Crm\Services\GeminiTranslationService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Arr;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;

class EditPost extends EditRecord
{
    use HasPreviewModal;
    use EditRecord\Concerns\Translatable;

    protected static string $resource = PostResource::class;

    protected array $originalDefaultLocaleData = [];

    protected function getHeaderActions(): array
    {
        return [
            PreviewAction::make(),
            Actions\DeleteAction::make(),
            Actions\LocaleSwitcher::make(),
        ];
    }

    public function mount($record): void
    {
        parent::mount($record);
        $this->updateOriginalLocaleData();
    }

    protected function onAfterSave(): void
    {
        $this->updateOriginalLocaleData();
    }

    public function updatedActiveLocale(string $newLocale): void
    {
        $defaultLocale = PostResource::getDefaultTranslatableLocale();

        if ($newLocale === $defaultLocale) {
            $this->form->fill(Arr::get($this->originalDefaultLocaleData, $defaultLocale, []));
            return;
        }

        // $this->form->fill($this->form->getState());

        $this->batchTranslate($defaultLocale, $newLocale);
    }

    protected function updateOriginalLocaleData(): void
    {
        $defaultLocale = PostResource::getDefaultTranslatableLocale();
        $this->originalDefaultLocaleData = $this->getRecord()->getTranslations(null, [$defaultLocale]);
    }

    private function batchTranslate(string $defaultLocale, string $newLocale): void
    {
        Notification::make()
            ->id('translation-in-progress')
            ->title('Auto-translating content...')
            ->info()
            ->persistent()
            ->send();

        $record = $this->getRecord();
        $translator = app(GeminiTranslationService::class);
        $translatableFields = ['title', 'meta_title', 'meta_description', 'content'];
        $textsToTranslate = [];
        $fieldMap = [];

        // Get the current form data for the active locale (which is $newLocale at this point)
        $currentFormData = $this->form->getState();

        // Get the current form data for the default locale. This reflects user edits.
        // We need this to determine the source for translation.
        $currentDefaultLocaleFormData = $this->form->getRawState(); // Get raw state to avoid any Livewire transformations

        // 1. Gather all texts that need translation
        foreach ($translatableFields as $field) {
            $sourceValue = Arr::get($currentDefaultLocaleFormData, $field); // Always use the current form state of the default locale as the source
            $targetValue = $record->getTranslation($field, $newLocale, false); // Saved target locale content from DB

            if ($field === 'content') {
                if (is_array($sourceValue)) {
                    foreach ($sourceValue as $blockIndex => $sourceBlock) {
                        $targetBlock = Arr::get($targetValue, $blockIndex);

                        // Markdown content
                        if ($sourceBlock['type'] === 'markdown' && !empty($sourceBlock['data']['content'])) {
                            $sourceContent = $sourceBlock['data']['content'];
                            $targetContent = Arr::get($targetBlock, 'data.content');
                            if (empty($targetContent) || $sourceContent !== $targetContent) {
                                $key = "content_{$blockIndex}_md";
                                $textsToTranslate[$key] = $sourceContent;
                                $fieldMap[$key] = ['field' => 'content', 'index' => $blockIndex, 'type' => 'markdown'];
                            }
                        }
                        // Figure alt text
                        if ($sourceBlock['type'] === 'figure') {
                            if (!empty($sourceBlock['data']['alt'])) {
                                $sourceAlt = $sourceBlock['data']['alt'];
                                $targetAlt = Arr::get($targetBlock, 'data.alt');
                                if (empty($targetAlt) || $sourceAlt !== $targetAlt) {
                                    $key = "content_{$blockIndex}_alt";
                                    $textsToTranslate[$key] = $sourceAlt;
                                    $fieldMap[$key] = ['field' => 'content', 'index' => $blockIndex, 'type' => 'alt'];
                                }
                            }
                            if (!empty($sourceBlock['data']['caption'])) {
                                $sourceCaption = $sourceBlock['data']['caption'];
                                $targetCaption = Arr::get($targetBlock, 'data.caption');
                                if (empty($targetCaption) || $sourceCaption !== $targetCaption) {
                                    $key = "content_{$blockIndex}_caption";
                                    $textsToTranslate[$key] = $sourceCaption;
                                    $fieldMap[$key] = ['field' => 'content', 'index' => $blockIndex, 'type' => 'caption'];
                                }
                            }
                        }
                    }
                }
            } else {
                // For simple string fields (title, meta_title, meta_description)
                // --- DEBUGGING START ---
            dd([
                'field' => $field,
                'sourceValue' => $sourceValue,
                'targetValue' => $targetValue,
                'isSourceEmpty' => $this->isFieldEmpty($sourceValue, $field),
                'isTargetEmpty' => $this->isFieldEmpty($targetValue, $field),
                'sourceEqualsTarget' => ($sourceValue === $targetValue), // For simple fields
                'conditionResult' => !$this->isFieldEmpty($sourceValue, $field) && ($this->isFieldEmpty($targetValue, $field) || $sourceValue !== $targetValue)
            ]);
            // --- DEBUGGING END ---
                    $textsToTranslate[$field] = $sourceValue;
                    $fieldMap[$field] = ['field' => $field];
                }
            }


        if (empty($textsToTranslate)) {
            Notification::make()->id('translation-in-progress')->title('Nothing to translate.')->success()->send();
            return;
        }

        // 2. Make a single API call
        $translations = $translator->translateMany($textsToTranslate, $defaultLocale, $newLocale);

        // 3. Update the form with the results
        $updatedData = $currentFormData; // Start with the current form data for the new locale

        foreach ($translations as $key => $translatedText) {
            if ($translatedText === null) continue;

            $map = $fieldMap[$key];
            $field = $map['field'];

            if ($field === 'content') {
                if (!isset($updatedData['content']) || !is_array($updatedData['content'])) {
                    $updatedData['content'] = [];
                }
                $blockIndex = $map['index'];
                $type = $map['type'];

                if (!isset($updatedData['content'][$blockIndex])) {
                    // If the block doesn't exist in the target form data, create a basic one
                    // This might happen if the source content structure changed significantly
                    $updatedData['content'][$blockIndex] = ['type' => $type === 'markdown' ? 'markdown' : 'figure', 'data' => []];
                }

                if ($type === 'markdown') {
                    $updatedData['content'][$blockIndex]['data']['content'] = $translatedText;
                } elseif ($type === 'alt') {
                    $updatedData['content'][$blockIndex]['data']['alt'] = $translatedText;
                } elseif ($type === 'caption') {
                    $updatedData['content'][$blockIndex]['data']['caption'] = $translatedText;
                }
            } else {
                $updatedData[$field] = $translatedText;
            }
        }

        if (!empty($updatedData)) {
            $this->form->fill($updatedData);
        }

        $this->sendCompletionNotification(count($translations), array_keys(array_filter($translations, 'is_null')));


        if (empty($textsToTranslate)) {
            Notification::make()->id('translation-in-progress')->title('Nothing to translate.')->success()->send();
            return;
        }

        // 2. Make a single API call
        $translations = $translator->translateMany($textsToTranslate, $defaultLocale, $newLocale);

        // 3. Update the form with the results
        // We need to merge translations into the current form state for the new locale
        //$updatedData = $currentNewLocaleData; // Start with current form state for the new locale

        foreach ($translations as $key => $translatedText) {
            if ($translatedText === null) continue;

            $map = $fieldMap[$key];
            $field = $map['field'];

            if ($field === 'content') {
                // Ensure content array exists and is correctly structured
                if (!isset($updatedData['content']) || !is_array($updatedData['content'])) {
                    $updatedData['content'] = [];
                }
                $blockIndex = $map['index'];
                $type = $map['type'];

                // Ensure the block at blockIndex exists before trying to set its data
                if (!isset($updatedData['content'][$blockIndex])) {
                    // This means the structure of the content block changed between source and target,
                    // or the block was not present in the target form data.
                    // For now, we'll skip, but a more robust solution might involve creating the block.
                    continue;
                }

                if ($type === 'markdown') {
                    $updatedData['content'][$blockIndex]['data']['content'] = $translatedText;
                } elseif ($type === 'alt') {
                    $updatedData['content'][$blockIndex]['data']['alt'] = $translatedText;
                } elseif ($type === 'caption') {
                    $updatedData['content'][$blockIndex]['data']['caption'] = $translatedText;
                }
            } else {
                $updatedData[$field] = $translatedText;
            }
        }

        if (!empty($updatedData)) {
            $this->form->fill($updatedData);
        }

        $this->sendCompletionNotification(count($translations), array_keys(array_filter($translations, 'is_null')));
    }






    private function sendCompletionNotification(int $total, array $failedKeys): void
    {
        $notification = Notification::make()->id('translation-in-progress');
        $successCount = $total - count($failedKeys);

        if (empty($failedKeys)) {
            $notification->title('Auto-translation Complete')->body("Successfully translated {$successCount} text blocks.")->success();
        } else {
            $notification->title('Translation Partially Complete')->body("Translated {$successCount} blocks. Failed: " . count($failedKeys) . ".")->warning();
        }
        $notification->send();
    }

    private function isFieldEmpty($content, string $fieldName): bool
    {
        if ($fieldName === 'content') {
            if (!is_array($content) || empty($content)) {
                return true;
            }
            // Check if all translatable parts within content blocks are empty
            foreach ($content as $block) {
                if ($block['type'] === 'markdown' && !empty($block['data']['content'])) {
                    return false; // Found non-empty markdown content
                }
                if ($block['type'] === 'figure') {
                    if (!empty($block['data']['alt']) || !empty($block['data']['caption'])) {
                        return false; // Found non-empty alt or caption
                    }
                }
            }
            return true; // All translatable parts are empty
        }
        return empty($content);
    }

    protected function getPreviewModalUrl(): ?string
    {
        if ($this->record && $this->record->postCategory) {
            $this->generatePreviewSession();
            switch ($this->record->postCategory->slug) {
                case 'page':
                    return route('page', [
                        'post' => $this->record->slug,
                        'previewToken' => $this->previewToken,
                    ]);
                default:
                    return route('posts.show', [
                        'post' => $this->record->slug,
                        'previewToken' => $this->previewToken,
                    ]);
            }
        }
        return null;
    }



}
