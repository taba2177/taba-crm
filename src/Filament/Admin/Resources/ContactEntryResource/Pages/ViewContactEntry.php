<?php

namespace Taba\Crm\Filament\Admin\Resources\ContactEntryResource\Pages;

use Taba\Crm\Filament\Admin\Resources\ContactEntryResource;
use Filament\Resources\Pages\ViewRecord;

class ViewContactEntry extends ViewRecord
{
    protected static string $resource = ContactEntryResource::class;

    protected function getActions(): array
    {
        return [];
    }
}