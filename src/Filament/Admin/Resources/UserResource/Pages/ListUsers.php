<?php

namespace Taba\Crm\Filament\Admin\Resources\UserResource\Pages;

use Taba\Crm\Filament\Admin\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    /**
     * The resource model.
     */
    protected static string $resource = UserResource::class;

    /**
     * The header actions.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->translateLabel(),
        ];
    }
}
