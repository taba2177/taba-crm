<?php

// FILE: packages/taba/crm/src/CrmPlugin.php

namespace Taba\Crm;

use App\Filament\Admin\Themes\Awesome;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Colors\Color;
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
            ->pages([
                \Filament\Pages\Dashboard::class,
            ])
            ->resources([
                \Taba\Crm\Filament\Admin\Resources\PostResource::class,
                \Taba\Crm\Filament\Admin\Resources\PostCategoryResource::class,
                \Awcodes\Curator\Resources\Media\MediaResource::class,
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
        ->colors($this->resolveColors())
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
        ->label(__('Media'))
        ->navigationGroup('collections')
        // ->navigationGroup(__('Collections'))
        ->showBadge())
        ->databaseNotifications(
            \Illuminate\Support\Facades\Schema::hasTable('notifications')
        )
        ->favicon(asset('images/favicon.png'))
        ->brandLogo(fn () => new \Illuminate\Support\HtmlString(view('crm::components.logo')->render()))
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
        ->viteTheme('vendor/taba/crm/src/resources/css/filament-theme.css')
        ->middleware([
            \Taba\Crm\Http\Middleware\ApplyBrandColors::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        // Apply brand colors + font from CRM settings (DB) with config fallback
        $this->applyBrandTheme($panel);

        // Fix AuthUIEnhancer layout — Tailwind JIT/utility classes (flex, lg:flex-row-reverse,
        // lg:w-[var(--form-panel-width)], bg-[var(--...)]) are not included in
        // Filament's pre-built CSS, so the auth split-panel layout breaks.
        // We inject equivalent plain CSS rules that don't require a Tailwind build.
        \Filament\Support\Facades\FilamentView::registerRenderHook(
            \Filament\View\PanelsRenderHook::HEAD_END,
            fn () => '
            <style>
            /*
             split-panel fix
               Filament v4 pre-builds its own Tailwind v4 CSS which does NOT include
               the utility classes used in AuthUIEnhancer blade templates.
               We supply plain CSS equivalents for every class used in custom-auth-layout.blade.php. */

            /* --- Wrapper --- */
            .custom-auth-wrapper {
                display: flex;
                flex-direction: column;
                width: 100%;
                min-height: 100vh;
            }
            @media (min-width: 1024px) {
                .custom-auth-wrapper { flex-direction: row-reverse; }
            }

            /* --- Empty (hero) panel --- */
            .custom-auth-empty-panel {
                position: relative;
                display: flex;
                flex-direction: column;
                flex-grow: 1;
                justify-content: center;
                padding-left: 1rem;
                padding-right: 1rem;
                background-color: var(--empty-panel-background-color, var(--primary-500));
                min-height: 200px;
            }

            /* --- Form panel --- */
            .custom-auth-form-panel {
                display: flex;
                flex-direction: column;
                justify-content: center;
                width: 100%;
                padding: 3rem 1rem;
                background-color: var(--form-panel-background-color, transparent);
                box-sizing: border-box;
                overflow: hidden;
            }
            @media (min-width: 640px) {
                .custom-auth-form-panel { padding-left: 1.5rem; padding-right: 1.5rem; }
            }
            @media (min-width: 1024px) {
                .custom-auth-form-panel {
                    width: var(--form-panel-width, 40%) !important;
                    flex-shrink: 0;
                    padding-left: 5rem;
                    padding-right: 5rem;
                }
            }
            @media (min-width: 1280px) {
                .custom-auth-form-panel { padding-left: 9rem; padding-right: 9rem; }
            }

            /* --- Form inner wrapper --- */
            .custom-auth-form-wrapper {
                margin-left: auto;
                margin-right: auto;
                width: 100%;
                max-width: 24rem;
            }

            /* --- Sidebar scrollbar hide (RTL fix) --- */
            .fi-sidebar-nav {
                scrollbar-width: none;
                -ms-overflow-style: none;
            }
            .fi-sidebar-nav::-webkit-scrollbar { display: none; }
            </style>
            '
        );

        // Inject admin.css as a <link> (not viteTheme) so Filament's own app.css is preserved.
        // viteTheme() replaces Filament's default CSS bundle; using a render hook adds on top of it.
        $manifestPath = public_path('build/manifest.json');
        $cssKeys = [
            'resources/css/admin.css',
            'vendor/taba/crm/src/resources/css/admin.css',
            'packages/taba/crm/src/resources/css/admin.css',
        ];

        if (file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true) ?? [];
            $assetFile = null;
            foreach ($cssKeys as $key) {
                if (isset($manifest[$key]['file'])) {
                    $assetFile = $manifest[$key]['file'];
                    break;
                }
            }
            if ($assetFile) {
                $assetUrl = asset('build/' . $assetFile);
                \Filament\Support\Facades\FilamentView::registerRenderHook(
                    \Filament\View\PanelsRenderHook::HEAD_END,
                    fn () => '<link rel="stylesheet" href="' . e($assetUrl) . '">',
                );
            }
        }

    }

    protected function resolveColors(): array
    {
        try {
            $primary   = \Taba\Crm\Models\CrmSetting::get('crm_brand_primary_color', config('crm.brand.primary_color', '#0ea5e9'));
            $grayName  = \Taba\Crm\Models\CrmSetting::get('crm_brand_gray_palette', config('crm.brand.gray_palette', 'Slate'));
        } catch (\Throwable) {
            $primary   = config('crm.brand.primary_color', '#0ea5e9');
            $grayName  = config('crm.brand.gray_palette', 'Slate');
        }

        return [
            'primary' => Color::hex($primary),
            'gray'    => self::resolveNamedColor($grayName, Color::Slate),
            'danger'  => Color::Rose,
            'info'    => Color::Blue,
            'success' => Color::Teal,
            'warning' => Color::Amber,
        ];
    }

    protected static function resolveNamedColor(string $name, array $fallback): array
    {
        $const = strtoupper($name[0]) . strtolower(substr($name, 1));

        if (defined(Color::class . '::' . $const)) {
            return constant(Color::class . '::' . $const);
        }

        return $fallback;
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