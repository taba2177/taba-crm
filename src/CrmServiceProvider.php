<?php

namespace Taba\Crm;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Filament\Support\Facades\FilamentView;



class CrmServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        FilamentView::registerRenderHook(
            'panels::head.start',
            fn (): string => '<meta name="robots" content="noindex,nofollow">'
        );
        $this->mergeConfigFrom(__DIR__.'/config/crm.php', 'crm');

    }
    protected function loadSeeders($seed_list){
            $this->callAfterResolving(DatabaseSeeder::class, function ($seeder) use ($seed_list) {
                    foreach ((array) $seed_list as $path) {
                        $seeder->call($path);
                        // here goes the code that will print out in console that the migration was succesful
                    }
                });
            }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // // load seeders
        // $this->loadSeeders([
        //     'Taba\\Crm\\Database\\Seeders\\DatabaseSeeder',
        // ]);
        foreach (config('crm.middleware', []) as $alias => $class) {
            $this->app['router']->aliasMiddleware($alias, $class);
        }

        // $this->loadViewsFrom(__DIR__.'/../views', 'crm');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'crm');

        Filament::serving(function () {
            Filament::registerResources([
                \Taba\Crm\Filament\Resources\PostResource::class,
                \Taba\Crm\Filament\Resources\PostCategoryResource::class,
                \Taba\Crm\Filament\Resources\UserResource::class,
            ]);
        });

        if ($this->app->runningInConsole()) {
            $this->publishes([
                // Config
                __DIR__.'/config/crm.php' => config_path('crm.php'),
            ], ['crm', 'crm-config']);

            // Views
            $this->publishes([
                __DIR__.'/../views' => resource_path('views/'),
            ], ['crm', 'crm-views']);

            // Public Assets
            $this->publishes([
                __DIR__.'/../public' => public_path('vendor/'),
            ], ['crm', 'crm-public']);

            $this->publishes([
                __DIR__.'/resources/js' => resource_path('js/'),
                __DIR__.'/resources/css' => resource_path('css/'),
            ], ['crm','resources']);

            // Tailwind Configs (optional separate tag)
            $this->publishes([
                __DIR__.'/tailwind.config.js' => base_path('tailwind.config.js'),
                __DIR__.'/tailwind.admin.js' => base_path('tailwind.admin.js'),
                // vite
                __DIR__.'/vite.config.js' => base_path('vite.config.js'),
            ], ['crm','crm-tailwind']);

            $this->publishes([
                __DIR__.'/../database/seeders' => database_path('seeders'),
            ], 'db-seeders');

            // Migrations
            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'db-migrations');

            // Factories
            $this->publishes([
                __DIR__.'/../database/factories' => database_path('factories'),
            ], 'db-factories');
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
    }
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
    // }
}
