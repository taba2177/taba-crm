<?php

namespace Taba\Crm\Filament\Client\Widgets;

use Filament\Widgets\Widget;
use Taba\Crm\Models\ContactEntry;
use Taba\Crm\Models\CrmSetting;
use Taba\Crm\Models\Post;
use Taba\Crm\Models\PostCategory;

class WelcomeWidget extends Widget
{
    protected static string $view = 'crm::client.widgets.welcome';
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';

    public function getBusinessName(): ?string
    {
        return CrmSetting::get('business_name');
    }

    public function getSectionsCount(): int
    {
        return PostCategory::whereNotNull('section_component')
            ->where('is_active', true)
            ->count();
    }

    public function getPostsCount(): int
    {
        return Post::where('is_published', true)->count();
    }

    public function getMessagesCount(): int
    {
        return ContactEntry::count();
    }

    public function getUnreadMessagesCount(): int
    {
        return ContactEntry::where('is_read', false)->count();
    }
}
