<?php

namespace Taba\Crm\Filament\Resources\MenuResource\Pages;

use Taba\Crm\Filament\Resources\MenuResource;
use Filament\Resources\Pages\EditRecord;

class EditMenu extends EditRecord
{
    protected static string $resource = MenuResource::class;

    protected function getActions(): array
    {
        return [];
    }
}
