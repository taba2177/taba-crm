<?php

namespace Taba\Crm\Filament\Admin\Resources\PostCategoryResource\Pages;

use Taba\Crm\Filament\Admin\Resources\PostCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Taba\Crm\Filament\Admin\Resources\PostResource\Pages\HasPostPreview;

class ListPostCategories extends ListRecords
{

    use HasPostPreview;
    protected static string $resource = PostCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}