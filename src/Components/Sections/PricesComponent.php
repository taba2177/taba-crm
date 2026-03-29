<?php

namespace Taba\Crm\Components\Sections;

use Taba\Crm\Components\Concerns\HasTranslatableFields;
use Taba\Crm\Components\Contracts\SectionComponent;
use Taba\Crm\Components\Contracts\SectionLayout;
use Taba\Crm\Components\Fields\FieldFactory;
use Taba\Crm\Models\PostCategory;

class PricesComponent implements SectionComponent
{
    use HasTranslatableFields;

    public function key(): string
    {
        return 'prices';
    }

    public function label(): array
    {
        return ['ar' => 'الأسعار', 'en' => 'Prices'];
    }

    public function icon(): string
    {
        return 'heroicon-o-currency-dollar';
    }

    public function description(): array
    {
        return [
            'ar' => 'قسم الأسعار',
            'en' => 'Pricing section',
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
            FieldFactory::make('richtext', 'content', ['translatable' => true]),
            FieldFactory::make('number', 'price'),
            FieldFactory::make('toggle', 'is_featured'),
        ];
    }

    public function bladeView(): string
    {
        return 'crm::components.homepage.prices';
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
