<?php

namespace Taba\Crm\Filament\Resources\PageResource\Pages;

use Taba\Crm\Filament\Resources\PageResource;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    use HasPagePreview;

    protected static string $resource = PageResource::class;
}