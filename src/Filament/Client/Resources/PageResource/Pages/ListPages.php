<?php

namespace Taba\Crm\Filament\Client\Resources\PageResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Taba\Crm\Filament\Client\Resources\PageResource;

class ListPages extends ListRecords
{
    protected static string $resource = PageResource::class;

    public function getTitle(): string
    {
        return __('الصفحات');
    }
}
