<?php

namespace Taba\Crm\Filament\Client\Resources\SectionPostResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Taba\Crm\Filament\Client\Resources\SectionPostResource;
use Taba\Crm\Models\PostCategory;

class EditSectionPost extends EditRecord
{
    protected static string $resource = SectionPostResource::class;

    public PostCategory $category;

    public function mount(int|string $record): void
    {
        $this->category = PostCategory::findOrFail(request()->route('category'));
        parent::mount($record);
    }

    public function getTitle(): string
    {
        return __('تعديل') . ' - ' . $this->record->title;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return SectionPostResource::getUrl('index', ['category' => $this->category->id]);
    }
}
