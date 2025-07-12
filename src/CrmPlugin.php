<?php

namespace Taba\Crm;

use Filament\Contracts\Plugin;
use Filament\Panel;

// Import any third-party plugins your package uses
use Awcodes\Curator\CuratorPlugin;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Pboivin\FilamentPeek\FilamentPeekPlugin;
use Filament\SpatieLaravelTranslatablePlugin;

class CrmPlugin implements Plugin
{
    public function getId(): string
    {
        return 'taba-crm';
    }

    public function register(Panel $panel): void
    {
        // Register your package's Resources, Pages, and Widgets here
        $panel
            ->resources([
                \Taba\Crm\Filament\Resources\PostResource::class,
                \Taba\Crm\Filament\Resources\PostCategoryResource::class,
                \Taba\Crm\Filament\Resources\UserResource::class,
            ])
            ->pages([
                // \Taba\Crm\Filament\Pages\CustomPage::class,
            ])
            ->widgets([
                // \Taba\Crm\Filament\Widgets\CustomWidget::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
        // This is where you register the OTHER plugins your package depends on
        $panel
            ->plugin(BreezyCore::make()
            ->myProfile(
                shouldRegisterUserMenu: false,
                // shouldRegisterNavigation: true,
                hasAvatars: true,
            )->avatarUploadComponent(fn($fileUpload) => $fileUpload->disableLabel())
            ->enableTwoFactorAuthentication()
            )
            ->plugin(CuratorPlugin::make(__('Media'))
            ->navigationIcon('heroicon-o-photo')
            ->navigationSort(10)
            ->navigationGroup('Collections')
            ->navigationCountBadge())
            ->plugin(FilamentPeekPlugin::make()->disablePluginStyles())
            ->plugin(SpatieLaravelTranslatablePlugin::make()->defaultLocales(['ar', 'en']));
    }

    public static function make(): static
    {
        return app(static::class);
    }
}
