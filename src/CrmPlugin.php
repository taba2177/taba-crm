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
use Taba\Crm\Filament\Admin\Pages\GenerateComponentsFromAI;
use Taba\Crm\Filament\Admin\Pages\GenerateSiteFromAI;
use Taba\Crm\Filament\Admin\Widgets\LatestPosts;
use Taba\Crm\Filament\Admin\Widgets\PostStatsOverview;
use Taba\Crm\Filament\Admin\Widgets\VisitorAnalytics;
use Taba\Crm\Filament\Admin\Widgets\GlobalStatsOverview;
use Taba\Crm\Filament\Admin\Widgets\RecentActivities;
use BezhanSalleh\FilamentGoogleAnalytics\Widgets;
use DiogoGPinto\AuthUIEnhancer\AuthUIEnhancerPlugin;
use Filament\Enums\ThemeMode;
use Taba\Crm\Filament\Admin\Widgets\OthersAnalytics;
use Taba\Crm\Filament\Admin\Widgets\PaymentAnalytics;

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
                \Taba\Crm\Filament\Admin\Resources\PostResource::class,
                \Taba\Crm\Filament\Admin\Resources\PostCategoryResource::class,
                \Awcodes\Curator\Resources\MediaResource::class,
                \Taba\Crm\Filament\Admin\Resources\ContactEntryResource::class,
                \Taba\Crm\Filament\Admin\Resources\UserResource::class,
                \Taba\Crm\Filament\Admin\Resources\ServicePaymentResource::class,
                \Taba\Crm\Filament\Admin\Resources\CrmSettingResource::class,
                // \Althinect\FilamentSpatieRolesPermissions\Resources\RoleResource::class,
                // \Althinect\FilamentSpatieRolesPermissions\Resources\PermissionResource::class,
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
                VisitorAnalytics::class,
                LatestPosts::class,
                RecentActivities::class,
                // PostStatsOverview::class,
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
        ->databaseNotifications(
            \Illuminate\Support\Facades\Schema::hasTable('notifications')
        )
        ->favicon(asset('images/favicon.png'))
        ->brandLogo(fn () => view('components.logo'))
        ->plugins([
            // FilamentGoogleAnalyticsPlugin::make(),
            FilamentPeekPlugin::make(),
            AuthUIEnhancerPlugin::make()
                ->showEmptyPanelOnMobile(true)
                ->formPanelPosition('left')
                ->formPanelWidth('40%')
                ->mobileFormPanelPosition('bottom')
                ->emptyPanelBackgroundImageOpacity('70%')
                ->emptyPanelView('crm::filament.login-slideshow')

        ])->defaultThemeMode(ThemeMode::Dark)
        ->middleware([
            \Taba\Crm\Http\Middleware\ApplyBrandColors::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        // Apply brand colors + font from CRM settings (DB) with config fallback
        $this->applyBrandTheme($panel);

        // Only apply viteTheme if the CSS entry exists in the consumer's Vite manifest
        $cssPath = 'packages/taba/crm/src/resources/css/admin.css';
        $manifestPath = public_path('build/manifest.json');

        if (file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true) ?? [];
            if (isset($manifest[$cssPath])) {
                $panel->viteTheme($cssPath);
            }
        }
    }

    protected function applyBrandTheme(Panel $panel): void
    {
        try {
            $fontFamily = \Taba\Crm\Models\CrmSetting::get('crm_brand_font_family', config('crm.brand.font_family', 'Cairo'));
            $fontUrl    = \Taba\Crm\Models\CrmSetting::get('crm_brand_font_url', config('crm.brand.font_url', ''));

            if ($fontFamily) {
                $panel->font($fontFamily, url: $fontUrl ?: null);
            }
        } catch (\Throwable) {
            // DB not yet migrated or settings table missing — skip silently
        }
    }

    public static function make(): static
    {
        return app(static::class);
    }
}
