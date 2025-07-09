<?php


use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\Page;

class PreviewPost extends Page
{
    protected static string $resource = PostResource::class;

    protected static string $view = 'filament.resources.post-resource.pages.preview-post';
}