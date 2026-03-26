<?php

namespace Taba\Crm\Filament\Resources\ServicePaymentResource\Pages;

use Taba\Crm\Filament\Resources\ServicePaymentResource;
use Filament\Resources\Pages\ViewRecord;

class ViewServicePayments extends ViewRecord
{
    protected static string $resource = ServicePaymentResource::class;

    protected function getActions(): array
    {
        return [];
    }
}