<?php

// FILE: packages/taba/crm/src/CrmPlugin.php

namespace Taba\Crm;

use App\Filament\Admin\Themes\Awesome;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Awcodes\Curator\CuratorPlugin;
use BezhanSalleh\FilamentGoogleAnalytics\FilamentGoogleAnalyticsPlugin;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Pboivin\FilamentPeek\FilamentPeekPlugin;
use Filament\SpatieLaravelTranslatablePlugin;
use SebastianBergmann\CodeCoverage\Report\Html\Colors;
use Taba\Crm\Filament\Pages\GenerateComponentsFromAI;
use Taba\Crm\Filament\Pages\GenerateSiteFromAI;
use Taba\Crm\Filament\Widgets\LatestPosts;
use Taba\Crm\Filament\Widgets\PostStatsOverview;
use Taba\Crm\Filament\Widgets\VisitorAnalytics;
use Taba\Crm\Filament\Widgets\GlobalStatsOverview;
use Taba\Crm\Filament\Widgets\RecentActivities;
use BezhanSalleh\FilamentGoogleAnalytics\Widgets;
use DiogoGPinto\AuthUIEnhancer\AuthUIEnhancerPlugin;
use Filament\Enums\ThemeMode;
use Taba\Crm\Filament\Widgets\OthersAnalytics;
use Taba\Crm\Filament\Widgets\PaymentAnalytics;

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
                \Taba\Crm\Filament\Resources\ContactEntryResource::class,
                \Taba\Crm\Filament\Resources\UserResource::class,
                \Taba\Crm\Filament\Resources\ServicePaymentResource::class,
            ]);

        $panel
        ->plugin(BreezyCore::make()
        ->myProfile(
            shouldRegisterUserMenu: false,
            shouldRegisterNavigation: true,
            hasAvatars: true,
        )->avatarUploadComponent(fn($fileUpload) => $fileUpload->disableLabel())
         ->enableTwoFactorAuthentication()
        )->pages([
                GenerateSiteFromAI::class,
                GenerateComponentsFromAI::class,
            ])
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make()
                    ->gridColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 3
                    ])
                    ->sectionColumnSpan(1)
                    ->checkboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 4,
                    ])
                    ->resourceCheckboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                    ]),
            ])
        ->default()
        ->login()
        // ->registration()
        ->passwordReset()
        ->emailVerification()
        ->profile()
        ->widgets([
                GlobalStatsOverview::class,
                PaymentAnalytics::class,
                OthersAnalytics::class,
                VisitorAnalytics::class,
                // PostStatsOverview::class,
                // LatestPosts::class,
                // RecentActivities::class,
                // GlobalStatsOverview::class,
                // VisitorAnalytics::class,
                // PaymentAnalytics::class,
                // PostStatsOverview::class,
                // LatestPosts::class,
                // RecentActivities::class,
                // Widgets\PageViewsWidget::class,
                // Widgets\VisitorsWidget::class,
                // Widgets\ActiveUsersOneDayWidget::class,
                // Widgets\ActiveUsersSevenDayWidget::class,
                // Widgets\ActiveUsersTwentyEightDayWidget::class,
                // Widgets\SessionsWidget::class,
                // Widgets\SessionsByCountryWidget::class,
                // Widgets\SessionsDurationWidget::class,
                // Widgets\SessionsByDeviceWidget::class,
                // Widgets\MostVisitedPagesWidget::class,
                // Widgets\TopReferrersListWidget::class,
        ])
        ->plugin(CuratorPlugin::make(__('Media'))
        ->pluralLabel("الوسائط")
        ->navigationIcon('heroicon-o-photo')
        ->navigationSort(4)
        ->navigationLabel(__('Media'))
        ->navigationGroup('collections')
        // ->navigationGroup(__('Collections'))
        ->navigationCountBadge())
        ->databaseNotifications()
        ->favicon(asset('images/favicon.png'))
        ->brandLogo(fn () => view('components.logo'))
        ->plugins([
            // FilamentGoogleAnalyticsPlugin::make(),
            \Hasnayeen\Themes\ThemesPlugin::make()
            // ->canViewThemesPage(fn () => auth()->user()->hasRole('super_admin'))
            ->registerTheme(
                        [
                            \Hasnayeen\Themes\Themes\Sunset::class,
                        ],
                        override: true,
                    ),
            SpatieLaravelTranslatablePlugin::make()->defaultLocales(['ar', 'en']),
            FilamentPeekPlugin::make()->disablePluginStyles(),
            AuthUIEnhancerPlugin::make()
                ->showEmptyPanelOnMobile(true)
                ->formPanelPosition('left')
                ->formPanelWidth('40%')
                ->mobileFormPanelPosition('bottom')
                ->emptyPanelBackgroundImageOpacity('70%')
                ->emptyPanelBackgroundImageUrl('https://images.pexels.com/photos/466685/pexels-photo-466685.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2')

        ])->defaultThemeMode(ThemeMode::Dark)
        ->middleware([\Hasnayeen\Themes\Http\Middleware\SetTheme::class]);
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