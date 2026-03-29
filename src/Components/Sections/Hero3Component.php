<?php

namespace Taba\Crm\Components\Sections;

use Taba\Crm\Components\Concerns\HasTranslatableFields;
use Taba\Crm\Components\Contracts\SectionComponent;
use Taba\Crm\Components\Contracts\SectionLayout;
use Taba\Crm\Components\Fields\FieldFactory;
use Taba\Crm\Models\PostCategory;

class Hero3Component implements SectionComponent
{
    use HasTranslatableFields;

    public function key(): string
    {
        return 'hero-3';
    }

    public function label(): array
    {
        return ['ar' => 'القسم الرئيسي 3', 'en' => 'Hero 3'];
    }

    public function icon(): string
    {
        return 'heroicon-o-bolt';
    }

    public function description(): array
    {
        return [
            'ar' => 'القسم الرئيسي - النمط الثالث',
            'en' => 'Hero section - style 3',
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
            FieldFactory::make('image', 'image'),
            FieldFactory::make('text', 'subtitle', ['translatable' => true]),
        ];
    }

    public function itemFields(): array
    {
        return [];
    }

    public function bladeView(): string
    {
        return 'crm::components.homepage.hero';
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
