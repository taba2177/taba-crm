<?php

namespace Taba\Crm\Filament\Client\Pages;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Taba\Crm\Components\Registry\ComponentRegistry;
use Taba\Crm\Models\PostCategory;

class EditSection extends Page
{
    protected static bool $shouldRegisterNavigation = false;
    protected string $view = 'crm::client.edit-section';
    protected static ?string $slug = 'edit-section/{record}';

    public ?PostCategory $record = null;
    public ?array $data = [];

    public function mount(int|string $record): void
    {
        $this->record = PostCategory::findOrFail($record);

        $this->form->fill([
            'name' => $this->record->getTranslations('name'),
            'description' => $this->record->getTranslations('description'),
            'subtitle' => $this->record->getTranslations('subtitle'),
            'image' => $this->record->image,
        ]);
    }

    public function getTitle(): string
    {
        return __('تعديل') . ' - ' . ($this->record?->name ?? '');
    }

    public function form(Schema $schema): Schema
    {
        $component = ($this->record && ComponentRegistry::has($this->record->section_component))
            ? ComponentRegistry::resolve($this->record->section_component)
            : null;

        $fields = $component ? $component->sectionFields() : [];

        $basicFields = [];
        $mediaFields = [];
        $extraFields = [];

        foreach ($fields as $field) {
            $name = $field->getName();
            if (in_array($name, ['name', 'description', 'subtitle', 'content'])) {
                $basicFields[] = $field;
            } elseif (in_array($name, ['image', 'image_id'])) {
                $mediaFields[] = $field;
            } else {
                $extraFields[] = $field;
            }
        }

        $schema = [];

        if (!empty($basicFields)) {
            $schema[] = \Filament\Schemas\Components\Section::make(__('المعلومات الأساسية'))
                ->schema($basicFields)
                ->collapsible();
        }

        if (!empty($mediaFields)) {
            $schema[] = \Filament\Schemas\Components\Section::make(__('الوسائط'))
                ->schema($mediaFields)
                ->collapsible();
        }

        if (!empty($extraFields)) {
            $schema[] = \Filament\Schemas\Components\Section::make(__('إعدادات إضافية'))
                ->schema($extraFields)
                ->collapsible();
        }

        return $schema->schema($schema)->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if (isset($data['name'])) {
            $this->record->setTranslations('name', $data['name']);
        }
        if (isset($data['description'])) {
            $this->record->setTranslations('description', $data['description']);
        }
        if (isset($data['subtitle'])) {
            $this->record->setTranslations('subtitle', $data['subtitle']);
        }
        if (isset($data['image'])) {
            $this->record->image = $data['image'];
        }

        $this->record->save();

        Notification::make()
            ->title(__('تم الحفظ بنجاح'))
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Forms\Components\Actions\Action::make('save')
                ->label(__('حفظ'))
                ->submit('save'),
        ];
    }
}
