<?php

namespace Taba\Crm\Filament\Resources\PostCategoryResource\Pages;

use Taba\Crm\Filament\Resources\PostCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePostCategory extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;
    protected static string $resource = PostCategoryResource::class;
}
