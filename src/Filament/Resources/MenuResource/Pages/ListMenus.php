<?php

namespace Taba\Crm\Filament\Resources\MenuResource\Pages;

use Taba\Crm\Filament\Resources\MenuResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;

class ListMenus extends ListRecords
{
    protected static string $resource = MenuResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
