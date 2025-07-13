<?php

// FILE: packages/taba/crm/src/CrmServiceProvider.php

namespace Taba\Crm;

use Illuminate\Support\ServiceProvider;
use Taba\Crm\Commands\InstallCommand;

// Import the service providers from your package's dependencies
use Jeffgreco13\FilamentBreezy\FilamentBreezyServiceProvider;
use Awcodes\Curator\CuratorServiceProvider;
use Pboivin\FilamentPeek\FilamentPeekServiceProvider;

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
                // Programmatically register the service providers of third-party packages.
        // This makes their commands (like 'filament-breezy:install') available.
        $this->app->register(FilamentBreezyServiceProvider::class);
        $this->app->register(CuratorServiceProvider::class);
        $this->app->register(FilamentPeekServiceProvider::class);
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
                __DIR__.'/views' => resource_path('views/vendor/crm'),
            ], 'crm-views');

            $this->publishes([
                __DIR__.'/../public' => public_path('vendor/crm'),
            ], 'crm-public');

            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
                __DIR__.'/../database/seeders' => database_path('seeders'),
                __DIR__.'/../database/factories' => database_path('factories'),
            ], 'crm-database');

            $this->publishes([
                __DIR__.'/database/migrations' => database_path('migrations'),
            ], 'crm-migration');

            $this->publishes([
                __DIR__.'/resources/js' => resource_path('js/'),
                __DIR__.'/resources/css' => resource_path('css/'),
            ], ['crm','resources']);

            // Tailwind Configs (optional separate tag)
            $this->publishes([
                __DIR__.'/tailwind.config.js' => base_path('tailwind.config.js'),
                __DIR__.'/tailwind.admin.js' => base_path('tailwind.admin.js'),
                __DIR__.'/vite.config.js' => base_path('vite.config.js'),
                __DIR__.'/postcss.config.js' => base_path('postcss.config.js'),
                __DIR__.'/package.json' => base_path('package.json'),
            ], ['crm','crm-tailwind']);
        }
    }
}
