<?php

// FILE: packages/taba/crm/src/CrmServiceProvider.php

namespace Taba\Crm;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Taba\Crm\Commands\InstallCommand;
use Illuminate\Support\Facades\App;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;
use Taba\Crm\Components\Registry\ComponentRegistry;
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

        // Exclude our own ActivityResource from filament-logger's resource
        // logging. This is critical: the panel registers our ActivityResource
        // (model = Spatie\Activitylog Activity). Without the exclusion,
        // filament-logger observes the Activity model and logs every activity as
        // a new "Activity Created" activity — copying the previous row's
        // `properties` each time. That doubles the row on every write and
        // balloons the DB to gigabytes within seconds, hanging the machine on a
        // fresh install and timing out admin login.
        //
        // We set the config key directly rather than via mergeConfigFrom():
        // Laravel's mergeConfigFrom() is a *shallow* array_merge, so a package
        // config file cannot override a nested key (resources.exclude) once
        // filament-logger has merged its own defaults. Setting it here in the
        // register phase runs after filament-logger merges its config but before
        // its packageBooted() registers the model observers, so the exclusion
        // takes effect. See RecentActivities widget for the read-side guard.
        config([
            'filament-logger.resources.exclude' => array_values(array_unique(array_merge(
                (array) config('filament-logger.resources.exclude', []),
                [\Taba\Crm\Filament\Admin\Resources\ActivityResource::class],
            ))),
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set locale from config instead of hardcoding
        \App::setLocale(config('crm.locale', 'ar'));

        // Configure language switch from config (optional plugin)
        if (class_exists(\BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch::class)) {
            \BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch::configureUsing(function ($switch) {
                $switch->locales(config('crm.available_locales', ['ar', 'en']));
            });
        }

        Livewire::component('home', Home::class);

        // Register policies for package models explicitly since Laravel's
        // auto-discovery convention only works for App\Models\* → App\Policies\*.
        Gate::policy(\Taba\Crm\Models\Post::class, \Taba\Crm\Policies\PostPolicy::class);
        Gate::policy(\Taba\Crm\Models\PostCategory::class, \Taba\Crm\Policies\PostCategoryPolicy::class);
        Gate::policy(\Taba\Crm\Models\Page::class, \Taba\Crm\Policies\PagePolicy::class);
        Gate::policy(\Taba\Crm\Models\CrmSetting::class, \Taba\Crm\Policies\CrmSettingPolicy::class);
        Gate::policy(\Taba\Crm\Models\ContactEntry::class, \Taba\Crm\Policies\ContactEntryPolicy::class);
        Gate::policy(\Taba\Crm\Models\ServicePayment::class, \Taba\Crm\Policies\ServicePaymentPolicy::class);
        Gate::policy(\Taba\Crm\Models\User::class, \Taba\Crm\Policies\UserPolicy::class);
        Gate::policy(\Awcodes\Curator\Models\Media::class, \Taba\Crm\Policies\MediaPolicy::class);
        Gate::policy(\Spatie\Permission\Models\Role::class, \Taba\Crm\Policies\RolePolicy::class);

        // Load package assets with a namespace to prevent conflicts.
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // Load API routes if enabled
        if (config('crm.api.enabled', true)) {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
            $this->configureApiRateLimiting();
        }

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'crm');
        $this->loadJsonTranslationsFrom(__DIR__.'/../lang');
        $this->loadViewsFrom(__DIR__.'/views', 'crm');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Register middleware from the package's config file.
        foreach (config('crm.middleware', []) as $alias => $class) {
            $this->app['router']->aliasMiddleware($alias, $class);
        }

        // SEO bot-injection middleware
        $this->app['router']->aliasMiddleware('crm.seo', \Taba\Crm\Http\Middleware\InjectSeoForBots::class);

        // Agent-ready discovery header middleware
        $this->app['router']->aliasMiddleware('crm.discovery', \Taba\Crm\Http\Middleware\AddDiscoveryHeaders::class);

        // Discover built-in section components
        ComponentRegistry::discoverIn(
            __DIR__ . '/Components/Sections',
            'Taba\\Crm\\Components\\Sections'
        );

        // Register config-driven extra components
        ComponentRegistry::fromConfig(config('crm.extra_components', []));

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

            // Publish views from src/views
            $this->publishes([
                __DIR__.'/views' => resource_path('views/vendor/crm'),
                __DIR__.'/views/components/homepage/four-cards.blade.php' => resource_path('views/components/homepage/four-cards.blade.php'),
                __DIR__.'/views/components/breadcrumbs.blade.php' => resource_path('views/components/breadcrumbs.blade.php'),
                __DIR__.'/views/components/breadcrumbs2.blade.php' => resource_path('views/components/breadcrumbs2.blade.php'),
                __DIR__.'/views/components/figure.blade.php' => resource_path('views/components/figure.blade.php'),
                __DIR__.'/views/components/header.blade.php' => resource_path('views/components/header.blade.php'),
                __DIR__.'/views/components/footer.blade.php' => resource_path('views/components/footer.blade.php'),
                __DIR__.'/views/components/layouts' => resource_path('views/components/layouts'),
                __DIR__.'/views/components/templates' => resource_path('views/components/templates'),
                __DIR__.'/views/livewire' => resource_path('views/livewire'),
                __DIR__.'/views/posts' => resource_path('views/posts'),
                __DIR__.'/views/previews' => resource_path('views/previews'),
                __DIR__.'/views/filament' => resource_path('views/filament'),
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
            ], ['crm','resources']);

        }
    }

    /**
     * Configure rate limiting for the CRM API.
     */
    protected function configureApiRateLimiting(): void
    {
        RateLimiter::for('crm-api', function (\Illuminate\Http\Request $request) {
            $limit = config('crm.api.rate_limit', 60);

            return $request->user()
                ? Limit::perMinute($limit)->by($request->user()->id)
                : Limit::perMinute($limit)->by($request->ip());
        });
    }
}
