<?php

namespace Taba\Crm\Components\Sections;

use Taba\Crm\Components\Concerns\HasTranslatableFields;
use Taba\Crm\Components\Contracts\SectionComponent;
use Taba\Crm\Components\Contracts\SectionLayout;
use Taba\Crm\Components\Fields\FieldFactory;
use Taba\Crm\Models\PostCategory;

class Contact2Component implements SectionComponent
{
    use HasTranslatableFields;

    public function key(): string
    {
        return 'contact-2';
    }

    public function label(): array
    {
        return ['ar' => 'تواصل معنا 2', 'en' => 'Contact 2'];
    }

    public function icon(): string
    {
        return 'heroicon-o-phone';
    }

    public function description(): array
    {
        return [
            'ar' => 'قسم التواصل - النمط الثاني',
            'en' => 'Contact section - style 2',
        ];
    }

    public function layout(): SectionLayout
    {
        return SectionLayout::SINGLE;
    }

    public function sectionFields(): array
    {
        return [
            FieldFactory::make('text', 'name', ['translatable' => true]),
            FieldFactory::make('textarea', 'description', ['translatable' => true]),
            FieldFactory::make('text', 'phone'),
            FieldFactory::make('text', 'email'),
        ];
    }

    public function itemFields(): array
    {
        return [];
    }

    public function bladeView(): string
    {
        return 'crm::components.homepage.contact';
    }

    public function toApi(PostCategory $section): array
    {
        return [
            'id' => $section->id,
            'component' => $this->key(),
            'order' => $section->order,
            'is_active' => (bool) $section->is_active,
            'title' => $section->getTranslations('name'),
            'subtitle' => $section->getTranslations('description'),
            'items' => [],
        ];
    }

    public function rules(): array
    {
        return [
            'name' => 'required|array',
            'name.ar' => 'required|string|max:255',
        ];
    }

    public function maxItems(): ?int
    {
        return null;
    }
}
