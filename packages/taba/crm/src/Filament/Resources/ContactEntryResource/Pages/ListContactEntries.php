<?php

namespace Taba\Crm\Filament\Resources\ContactEntryResource\Pages;

use Taba\Crm\Filament\Resources\ContactEntryResource;
use Filament\Resources\Pages\ListRecords;

class ListContactEntries extends ListRecords
{
    protected static string $resource = ContactEntryResource::class;

    protected function getActions(): array
    {
        return [];
    }
}