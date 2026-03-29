<?php

namespace Taba\Crm\Filament\Client\Resources\SectionPostResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Taba\Crm\Filament\Client\Resources\SectionPostResource;
use Taba\Crm\Models\PostCategory;

class ViewSectionPost extends ViewRecord
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
        return $this->record->title;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->url(fn () => SectionPostResource::getUrl('edit', [
                    'category' => $this->category->id,
                    'record' => $this->record,
                ])),
            Actions\DeleteAction::make(),
        ];
    }
}
