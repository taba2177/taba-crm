<?php

namespace Taba\Crm\Filament\Client\Resources\PageResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Taba\Crm\Filament\Client\Resources\PageResource;

class ViewPage extends ViewRecord
{
    protected static string $resource = PageResource::class;

    public function getTitle(): string
    {
        return __('عرض الصفحة');
    }
}
