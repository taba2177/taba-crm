<?php

namespace Taba\Crm\Components\Sections;

use Taba\Crm\Components\Concerns\HasTranslatableFields;
use Taba\Crm\Components\Contracts\SectionComponent;
use Taba\Crm\Components\Contracts\SectionLayout;
use Taba\Crm\Components\Fields\FieldFactory;
use Taba\Crm\Models\PostCategory;

class SingleViewWhyChooseUsComponent implements SectionComponent
{
    use HasTranslatableFields;

    public function key(): string
    {
        return 'single-view-why-choose-us';
    }

    public function label(): array
    {
        return ['ar' => 'لماذا تختارنا', 'en' => 'Why Choose Us'];
    }

    public function icon(): string
    {
        return 'heroicon-o-hand-thumb-up';
    }

    public function description(): array
    {
        return [
            'ar' => 'قسم لماذا تختارنا',
            'en' => 'Why choose us section',
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
        ];
    }

    public function itemFields(): array
    {
        return [];
    }

    public function bladeView(): string
    {
        return 'crm::components.homepage.single-view-why-choose-us';
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
