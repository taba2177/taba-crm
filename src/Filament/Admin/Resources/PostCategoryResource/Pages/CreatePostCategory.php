<?php

namespace Taba\Crm\Filament\Admin\Resources\PostCategoryResource\Pages;

use Taba\Crm\Filament\Admin\Resources\PostCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePostCategory extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;
    protected static string $resource = PostCategoryResource::class;
}
