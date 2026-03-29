<?php

namespace Taba\Crm\Filament\Client\Resources\SectionPostResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Taba\Crm\Filament\Client\Resources\SectionPostResource;
use Taba\Crm\Models\PostCategory;

class CreateSectionPost extends CreateRecord
{
    protected static string $resource = SectionPostResource::class;

    public PostCategory $category;

    public function mount(): void
    {
        $this->category = PostCategory::findOrFail(request()->route('category'));
        parent::mount();
    }

    public function getTitle(): string
    {
        return __('إضافة عنصر جديد') . ' - ' . $this->category->name;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['post_category_id'] = $this->category->id;
        $data['order'] = $this->category->posts()->max('order') + 1;
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return SectionPostResource::getUrl('index', ['category' => $this->category->id]);
    }
}
