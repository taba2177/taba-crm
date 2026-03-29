<?php

namespace Taba\Crm\Components\Sections;

use Taba\Crm\Components\Concerns\HasTranslatableFields;
use Taba\Crm\Components\Contracts\SectionComponent;
use Taba\Crm\Components\Contracts\SectionLayout;
use Taba\Crm\Components\Fields\FieldFactory;
use Taba\Crm\Models\PostCategory;

class ServicesListComponent implements SectionComponent
{
    use HasTranslatableFields;

    public function key(): string
    {
        return 'services-list';
    }

    public function label(): array
    {
        return ['ar' => 'قائمة الخدمات', 'en' => 'Services List'];
    }

    public function icon(): string
    {
        return 'heroicon-o-clipboard-document-list';
    }

    public function description(): array
    {
        return [
            'ar' => 'عرض الخدمات كقائمة',
            'en' => 'Display services as a list',
        ];
    }

    public function layout(): SectionLayout
    {
        return SectionLayout::LIST;
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
        return [
            FieldFactory::make('text', 'title', ['translatable' => true]),
            FieldFactory::make('textarea', 'content', ['translatable' => true]),
            FieldFactory::make('image', 'image'),
            FieldFactory::make('icon', 'icon'),
            FieldFactory::make('url', 'url'),
        ];
    }

    public function bladeView(): string
    {
        return 'crm::components.homepage.services-list';
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
