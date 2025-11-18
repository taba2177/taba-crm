<?php

// FILE: packages/taba/crm/src/CrmServiceProvider.php

namespace Taba\Crm;

use Illuminate\Support\ServiceProvider;
use Taba\Crm\Commands\InstallCommand;
use Illuminate\Support\Facades\App;
use Livewire\Livewire;
use Taba\Crm\Livewire\Home;

class CrmServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Keep registration minimal. Do not programmatically register third-party
        // service providers here — allow those packages to register themselves.
        // Merge the package's config file with the application's.
        $this->mergeConfigFrom(__DIR__.'/../config/crm.php', 'crm');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set locale from config instead of hardcoding
        if (config('crm.locale')) {
            App::setLocale(config('crm.locale'));
        }

        // Configure language switch from config
        \BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch::configureUsing(function (\BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch $switch) {
            $switch->locales(config('crm.available_locales', ['ar', 'en']));
        });

        Livewire::component('home', Home::class);

        // Load package assets with a namespace to prevent conflicts.
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'crm');
        $this->loadViewsFrom(__DIR__.'/views', 'crm');
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
                __DIR__.'/views/components/logo.blade.php' => resource_path('views/components/logo.blade.php'),
                __DIR__.'/views/components/homepage/four-cards.blade.php' => resource_path('views/components/homepage/four-cards.blade.php'),
                __DIR__.'/views/components/layouts' => resource_path('views/components/layouts'),
                __DIR__.'/views/components/templates' => resource_path('views/components/templates'),
                __DIR__.'/views/livewire' => resource_path('views/livewire'),
                __DIR__.'/views/posts' => resource_path('views/posts'),
                __DIR__.'/views/previews' => resource_path('views/previews'),
                __DIR__.'/views/filament/pages' => resource_path('views/filament/pages'),
            ], 'crm-views');

            // Publish public assets only if the directory exists to avoid errors
            $publicDir = __DIR__.'/../public';
            if (is_dir($publicDir)) {
                $this->publishes([
                    $publicDir => public_path('vendor/crm'),
                ], 'crm-public');
            }

            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
                __DIR__.'/../database/seeders' => database_path('seeders'),
                __DIR__.'/../database/factories' => database_path('factories'),
            ], 'crm-database');

            $this->publishes([
                __DIR__.'/resources/js' => resource_path('js/'),
                __DIR__.'/resources/css' => resource_path('css/'),
                __DIR__.'/../tailwind.admin.js' => base_path('tailwind.admin.js'),
            ], ['crm','resources']);

        }
    }
}