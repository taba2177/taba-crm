<?php

namespace Taba\Crm\Filament\Admin\Resources\ContactEntryResource\Pages;

use Taba\Crm\Filament\Admin\Resources\ContactEntryResource;
use Filament\Resources\Pages\ListRecords;

class ListContactEntries extends ListRecords
{
    protected static string $resource = ContactEntryResource::class;

    protected function getActions(): array
    {
        return [];
    }
}