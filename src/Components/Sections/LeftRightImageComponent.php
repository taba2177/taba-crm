<?php

namespace Taba\Crm\Components\Sections;

use Taba\Crm\Components\Concerns\HasTranslatableFields;
use Taba\Crm\Components\Contracts\SectionComponent;
use Taba\Crm\Components\Contracts\SectionLayout;
use Taba\Crm\Components\Fields\FieldFactory;
use Taba\Crm\Models\PostCategory;

class LeftRightImageComponent implements SectionComponent
{
    use HasTranslatableFields;

    public function key(): string
    {
        return 'left-right-image';
    }

    public function label(): array
    {
        return ['ar' => 'صورة يمين ويسار', 'en' => 'Left Right Image'];
    }

    public function icon(): string
    {
        return 'heroicon-o-arrows-pointing-out';
    }

    public function description(): array
    {
        return [
            'ar' => 'قسم صورة مع نص يمين ويسار',
            'en' => 'Left right image with text section',
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
            FieldFactory::make('richtext', 'description', ['translatable' => true]),
            FieldFactory::make('image', 'image'),
        ];
    }

    public function itemFields(): array
    {
        return [];
    }

    public function bladeView(): string
    {
        return 'crm::components.homepage.left-right-image-sections';
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
