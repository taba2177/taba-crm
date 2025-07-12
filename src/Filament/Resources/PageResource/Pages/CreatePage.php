<?php

namespace Taba\Crm\Filament\Resources\PageResource\Pages;

use Taba\Crm\Filament\Resources\PageResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePage extends CreateRecord
{
    use HasPagePreview;

    protected static string $resource = PageResource::class;
}
