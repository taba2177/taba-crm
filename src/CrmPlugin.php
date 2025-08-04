<?php

// FILE: packages/taba/crm/src/CrmPlugin.php

namespace Taba\Crm;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Awcodes\Curator\CuratorPlugin;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Pboivin\FilamentPeek\FilamentPeekPlugin;
use Filament\SpatieLaravelTranslatablePlugin;
use Taba\Crm\Filament\Pages\GenerateComponentsFromAI;
use Taba\Crm\Filament\Pages\GenerateSiteFromAI;

class CrmPlugin implements Plugin
{
    public function getId(): string
    {
        return 'taba-crm';
    }

    public function register(Panel $panel): void
    {
        // Register the package's own resources, pages, and widgets.
        $panel
            ->resources([
                \Taba\Crm\Filament\Resources\PostResource::class,
                \Taba\Crm\Filament\Resources\PostCategoryResource::class,
                \Taba\Crm\Filament\Resources\UserResource::class,
                \Awcodes\Curator\Resources\MediaResource::class,
            ]);

        $panel
        ->plugin(BreezyCore::make()
        ->myProfile(
            shouldRegisterUserMenu: false,
            // shouldRegisterNavigation: true,

            hasAvatars: true,
        )->avatarUploadComponent(fn($fileUpload) => $fileUpload->disableLabel())
        // ->enableTwoFactorAuthentication()
        )->pages([
                // GenerateSiteFromAI::class,
                // GenerateComponentsFromAI::class,
            ])
        ->default()
        ->login()
        ->profile()
        ->plugin(CuratorPlugin::make(__('Media'))
        ->navigationIcon('heroicon-o-photo')
        ->navigationSort(10)
        ->navigationGroup('Collections')
        ->navigationCountBadge())
        ->plugin(FilamentPeekPlugin::make()->disablePluginStyles())
        ->plugin(SpatieLaravelTranslatablePlugin::make()->defaultLocales(['ar', 'en']));
    }

    public function boot(Panel $panel): void
    {
        $panel->viteTheme('vendor/taba/crm/src/resources/css/admin.css');
    }

    public static function make(): static
    {
        return app(static::class);
    }
}
