<?php

namespace Taba\Crm\Filament\Client\Resources\PageResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Taba\Crm\Filament\Client\Resources\PageResource;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    public function getTitle(): string
    {
        return __('تعديل الصفحة');
    }
}
