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
use Taba\Crm\Filament\Widgets\LatestPosts;
use Taba\Crm\Filament\Widgets\PostStatsOverview;
use Taba\Crm\Filament\Widgets\VisitorAnalytics;
use Taba\Crm\Filament\Widgets\GlobalStatsOverview;
use Taba\Crm\Filament\Widgets\RecentActivities;

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
                \Awcodes\Curator\Resources\MediaResource::class,
                \Taba\Crm\Filament\Resources\UserResource::class,
                \Althinect\FilamentSpatieRolesPermissions\Resources\RoleResource::class,
                \Althinect\FilamentSpatieRolesPermissions\Resources\PermissionResource::class,
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
                GenerateSiteFromAI::class,
                GenerateComponentsFromAI::class,
            ])
        ->default()
        ->login()
        ->registration()
        ->passwordReset()
        ->emailVerification()
        ->profile()
        ->widgets([
                PostStatsOverview::class,
                LatestPosts::class,
                VisitorAnalytics::class,
                GlobalStatsOverview::class,
                RecentActivities::class,
            ])
        ->plugin(CuratorPlugin::make(__('Media'))
                        ->pluralLabel(__('Media'))
        ->navigationIcon('heroicon-o-photo')
        ->navigationSort(10)
        // ->navigationGroup('Collections')
        ->navigationGroup(__('Media'))
        ->navigationCountBadge())
        ->plugin(FilamentPeekPlugin::make()->disablePluginStyles())
        ->plugin(SpatieLaravelTranslatablePlugin::make()->defaultLocales(['ar', 'en']))
        ->plugin(\BezhanSalleh\FilamentShield\FilamentShieldPlugin::make());
    }

    public function boot(Panel $panel): void
    {
        // $panel->viteTheme('vendor/taba/crm/src/resources/css/admin.css');
        $panel->viteTheme('packages/taba/crm/src/resources/css/admin.css');
    }

    public static function make(): static
    {
        return app(static::class);
    }
}

// add suitable roles and polices for my project taba/crm so when i install it in any project will be ready for tow users ,me and the client       │
// │   and he can edit and add and save posts and categories but he cant change see the component_section or the ai tools
// Here's the plan:
//    1. Create "Client" Role: I'll create a new role named "Client".
//    2. Sync Permissions: I'll run the permissions:sync command to ensure all necessary permissions for Posts and Categories are generated.
//    3. Create a Seeder for Role-Permission Assignment: I'll create a new database seeder to programmatically assign the relevant permissions (view,
//       create, edit, delete posts and categories) to the "Client" role. This makes it easy to set up in any new project.
//    4. Restrict AI Tools Access: I'll modify the GenerateComponentsFromAI and GenerateSiteFromAI pages to restrict access to only "Super Admin" users.