<?php

namespace Taba\Crm\Filament\Client\Pages;

use Filament\Pages\Page;
use Taba\Crm\Models\ContactEntry;
use Taba\Crm\Models\CrmSetting;
use Taba\Crm\Models\Post;
use Taba\Crm\Models\PostCategory;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = null;
    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'crm::client.dashboard';

    public static function getNavigationLabel(): string
    {
        return __('الرئيسية');
    }

    public static function getSlug(): string
    {
        return 'dashboard';
    }

    public function getTitle(): string
    {
        return __('لوحة التحكم');
    }

    public function getStats(): array
    {
        return [
            'sections' => PostCategory::whereNotNull('section_component')->where('is_active', true)->count(),
            'posts' => Post::where('is_published', true)->count(),
            'messages' => ContactEntry::count(),
        ];
    }

    public function getRecentMessages(): \Illuminate\Support\Collection
    {
        return ContactEntry::latest()->take(5)->get();
    }

    public function getBusinessName(): ?string
    {
        return CrmSetting::get('business_name');
    }
}
