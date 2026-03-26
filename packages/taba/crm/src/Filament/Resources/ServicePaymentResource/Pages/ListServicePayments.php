<?php

namespace Taba\Crm\Filament\Resources\ServicePaymentResource\Pages;

use Taba\Crm\Filament\Resources\ServicePaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServicePayments extends ListRecords
{
    protected static string $resource = ServicePaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}