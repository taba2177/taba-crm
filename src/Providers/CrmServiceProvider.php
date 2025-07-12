<?php

namespace Taba\Crm\Providers;

use Taba\Crm\Filament\Auth\Login;
use Awcodes\Curator\CuratorPlugin;
use Awcodes\FilamentGravatar\GravatarPlugin;
use Awcodes\FilamentGravatar\GravatarProvider;
use BezhanSalleh\FilamentExceptions\FilamentExceptionsPlugin;
use Croustibat\FilamentJobsMonitor\FilamentJobsMonitorPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Pboivin\FilamentPeek\FilamentPeekPlugin;
use Filament\SpatieLaravelTranslatablePlugin;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentView;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class CrmServiceProvider extends PanelProvider
{
    public function register(): void
    {
        FilamentView::registerRenderHook(
            'panels::head.start',
            fn (): string => '<meta name="robots" content="noindex,nofollow">'
        );
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(realpath(dirname(__DIR__) . '/database/migrations'));
        $this->loadRoutesFrom(realpath(dirname(__DIR__) . '/routes/web.php'));
        $this->loadViewsFrom(realpath(dirname(__DIR__) . '/resources/views'), 'crm');
        $this->loadTranslationsFrom(realpath(dirname(__DIR__) . '/lang'), 'crm');

        $this->publishes([
            realpath(dirname(__DIR__) . '/config/crm.php') => config_path('crm.php'),
        ], 'config');

        $this->publishes([
            realpath(dirname(__DIR__) . '/resources/views') => resource_path('views/vendor/crm'),
        ], 'views');

        $this->publishes([
            realpath(dirname(__DIR__) . '/public') => public_path('vendor/crm'),
        ], 'public');
    }

    public function panel(Panel $panel): Panel
    {

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->profile()
            ->spa()
            ->databaseNotifications()
            // ->navigationItems([
            // NavigationItem::make('Analytics')
            //     ->url('https://filament.pirsch.io', shouldOpenInNewTab: true)
            //     ->icon('heroicon-o-presentation-chart-line')
            //     ->group('Reports')
            //     ->sort(3),
            // ])
            ->plugins([
                BreezyCore::make()
                    ->myProfile(
                        shouldRegisterUserMenu: false,
                        // shouldRegisterNavigation: true,
                        hasAvatars: true,
                    )
                    ->avatarUploadComponent(fn($fileUpload) => $fileUpload->disableLabel())
                    ->enableTwoFactorAuthentication(),
                    CuratorPlugin::make(__('Media'))
                        ->navigationIcon('heroicon-o-photo')
                        ->navigationSort(10)
                        ->navigationGroup('Collections')
                        ->navigationCountBadge(),
                // FilamentJobsMonitorPlugin::make()
                //     ->navigationCountBadge(),
                //     // ->navigationGroup('settings'),

                FilamentPeekPlugin::make()->disablePluginStyles(),

                // FilamentExceptionsPlugin::make(),
                // filament/spatie-laravel-translatable-plugin setdefault locale to 'en','ar' in config
                SpatieLaravelTranslatablePlugin::make()
                    ->defaultLocales(['ar', 'en']),


                GravatarPlugin::make(),
            ])
            ->defaultAvatarProvider(GravatarProvider::class)
            ->favicon(asset('/favicon-32x32.png'))
            ->brandLogo(fn () => view('components.logo'))
                ->navigationGroups([
                    'Collections',
                ])
            ->colors([
                'primary' => Color::Cyan,
                'secondery' => Color::Lime,
            ])
            ->viteTheme('vendor/crm/css/admin.css')
            ->discoverResources(in: __DIR__ . '/../Filament/Resources', for: 'Taba\\Crm\\Filament\\Resources')
            ->discoverPages(in: __DIR__ . '/../Filament/Pages', for: 'Taba\\Crm\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: __DIR__ . '/../Filament/Widgets', for: 'Taba\\Crm\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,

           ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
