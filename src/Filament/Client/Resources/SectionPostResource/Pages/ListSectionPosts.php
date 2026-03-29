<?php

namespace Taba\Crm\Filament\Client\Resources\SectionPostResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Taba\Crm\Filament\Client\Resources\SectionPostResource;
use Taba\Crm\Models\PostCategory;

class ListSectionPosts extends ListRecords
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
        return $this->category->name;
    }

    protected function getTableQuery(): ?\Illuminate\Database\Eloquent\Builder
    {
        return parent::getTableQuery()->where('post_category_id', $this->category->id);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->url(fn () => SectionPostResource::getUrl('create', ['category' => $this->category->id])),
        ];
    }
}
