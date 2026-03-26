<?php

namespace Taba\Crm\Filament\Resources\ServicePaymentResource\Pages;

use Taba\Crm\Filament\Resources\ServicePaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServicePayment extends EditRecord
{
    protected static string $resource = ServicePaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}