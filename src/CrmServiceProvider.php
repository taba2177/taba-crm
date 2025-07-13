<?php

// FILE: packages/taba/crm/src/CrmServiceProvider.php

namespace Taba\Crm;

use Illuminate\Support\ServiceProvider;
use Taba\Crm\Commands\InstallCommand;

class CrmServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Merge the package's config file with the application's.
        $this->mergeConfigFrom(__DIR__.'/../config/crm.php', 'crm');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load package assets with a namespace to prevent conflicts.
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'crm');
        $this->loadViewsFrom(__DIR__.'/../views', 'crm');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Register middleware from the package's config file.
        foreach (config('crm.middleware', []) as $alias => $class) {
            $this->app['router']->aliasMiddleware($alias, $class);
        }

        // Only register commands and publishable assets when running in the console.
        if ($this->app->runningInConsole()) {
            // Register the custom 'crm:install' command.
            $this->commands([
                InstallCommand::class,
            ]);

            // Define publishable assets with tags for user control.
            $this->publishes([
                __DIR__.'/../config/crm.php' => config_path('crm.php'),
            ], 'crm-config');

            $this->publishes([
                __DIR__.'/../views' => resource_path('views/vendor/crm'),
            ], 'crm-views');

            $this->publishes([
                __DIR__.'/../public' => public_path('vendor/crm'),
            ], 'crm-public');

            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
                __DIR__.'/../database/seeders' => database_path('seeders'),
                __DIR__.'/../database/factories' => database_path('factories'),
            ], 'crm-database');
        }
    }
}

        // if ($this->app->runningInConsole()) {
        //     //publish tailwind config
        //     //php artisan vendor:publish --tag=tailwind-config --force
        //     $this->publishes([
        //         __DIR__.'/tailwind.config.js' => base_path('tailwind.config.js'),
        //     ], 'tailwind-config');
        //     //publish tailwind admin config
        //     //php artisan vendor:publish --tag=tailwind-admin-config --force
        //     $this->publishes([
        //         __DIR__.'/tailwind.admin.js' => base_path('tailwind.admin.js'),
        //     ], 'tailwind-admin-config');
        //     //publish resources css and js from assets to resources js and css folders
        //     //php artisan vendor:publish --tag=resources --force
        //     $this->publishes([
        //         __DIR__.'/../resources/js' => resource_path('js/'),
        //         __DIR__.'/../resources/css' => resource_path('css/'),
        //     ], 'resources');
        //     //php artisan vendor:publish --tag=config --force
        //     $this->publishes([
        //         __DIR__.'/config/crm.php' => config_path('crm.php'),
        //     ], 'config');
        //     //php artisan vendor:publish --tag=views --force
        //     $this->publishes([
        //         __DIR__.'/views' => resource_path('views/vendor/crm'),
        //     ], 'views');
        //     //php artisan vendor:publish --tag=public --force
        //     $this->publishes([
        //         __DIR__.'/public' => public_path('vendor/crm'),
        //     ], 'public');

        //     $this->publishes([
        //         __DIR__.'/../database/seeders' => database_path('seeders'),
        //     ], 'seeders');

        //     //publish migrations
        //     $this->publishes([
        //         __DIR__.'/../database/migrations' => database_path('migrations'),
        //     ], 'migrations');
        //     //publish factories
        //     $this->publishes([
        //         __DIR__.'/../database/factories' => database_path('factories'),
        //     ], 'factories');
        // }

    // public function panel(Panel $panel): Panel
    // {

    //     return $panel
    //         ->default()
    //         ->id('admin')
    //         ->path('admin')
    //         ->login(Login::class)
    //         ->profile()
    //         ->spa()
    //         ->databaseNotifications()
    //         // ->navigationItems([
    //         // NavigationItem::make('Analytics')
    //         //     ->url('https://filament.pirsch.io', shouldOpenInNewTab: true)
    //         //     ->icon('heroicon-o-presentation-chart-line')
    //         //     ->group('Reports')
    //         //     ->sort(3),
    //         // ])
    //         ->plugins([
    //             BreezyCore::make()
    //                 ->myProfile(
    //                     shouldRegisterUserMenu: false,
    //                     // shouldRegisterNavigation: true,
    //                     hasAvatars: true,
    //                 )
    //                 ->avatarUploadComponent(fn($fileUpload) => $fileUpload->disableLabel())
    //                 ->enableTwoFactorAuthentication(),
    //                 CuratorPlugin::make(__('Media'))
    //                     ->navigationIcon('heroicon-o-photo')
    //                     ->navigationSort(10)
    //                     ->navigationGroup('Collections')
    //                     ->navigationCountBadge(),
    //             // FilamentJobsMonitorPlugin::make()
    //             //     ->navigationCountBadge(),
    //             //     // ->navigationGroup('settings'),

    //             FilamentPeekPlugin::make()->disablePluginStyles(),

    //             // FilamentExceptionsPlugin::make(),
    //             // filament/spatie-laravel-translatable-plugin setdefault locale to 'en','ar' in config
    //             SpatieLaravelTranslatablePlugin::make()
    //                 ->defaultLocales(['ar', 'en']),


    //             GravatarPlugin::make(),
    //         ])
    //         ->defaultAvatarProvider(GravatarProvider::class)
    //         ->favicon(asset('/favicon-32x32.png'))
    //         ->brandLogo(fn () => view('components.logo'))
    //             ->navigationGroups([
    //                 'Collections',
    //             ])
    //         ->colors([
    //             'primary' => Color::Cyan,
    //             'secondery' => Color::Lime,
    //         ])
    //         ->viteTheme('vendor/crm/css/admin.css')
    //         ->discoverResources(in: __DIR__ . '/../Filament/Resources', for: 'Taba\\Crm\\Filament\\Resources')
    //         ->discoverPages(in: __DIR__ . '/../Filament/Pages', for: 'Taba\\Crm\\Filament\\Pages')
    //         ->pages([
    //             Pages\Dashboard::class,
    //         ])
    //         ->discoverWidgets(in: __DIR__ . '/../Filament/Widgets', for: 'Taba\\Crm\\Filament\\Widgets')
    //         ->widgets([
    //             Widgets\AccountWidget::class,
    //         ])
    //         ->middleware([
    //             EncryptCookies::class,
    //             AddQueuedCookiesToResponse::class,
    //             StartSession::class,
    //             AuthenticateSession::class,
    //             ShareErrorsFromSession::class,
    //             VerifyCsrfToken::class,
    //             SubstituteBindings::class,
    //             DisableBladeIconComponents::class,
    //             DispatchServingFilamentEvent::class,

    //        ])
    //         ->authMiddleware([
    //             Authenticate::class,
    //         ]);
    //
