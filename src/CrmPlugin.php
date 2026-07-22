<?php

namespace Taba\Crm;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Colors\Color;
use Awcodes\Curator\CuratorPlugin;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Pboivin\FilamentPeek\FilamentPeekPlugin;
use DiogoGPinto\AuthUIEnhancer\AuthUIEnhancerPlugin;

class CrmPlugin implements Plugin
{
    public function getId(): string
    {
        return 'taba-crm';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->default()
            ->login()
            ->passwordReset()
            ->emailVerification()
            ->profile()
            ->colors($this->resolveColors())
            ->databaseNotifications(
                \Illuminate\Support\Facades\Schema::hasTable('notifications')
            )
            ->favicon(asset('images/favicon.png'))
            ->brandLogo(fn () => new \Illuminate\Support\HtmlString(
                view('crm::components.logo')->render()
            ));

        // ── Auto-discover package Filament resources, pages & widgets ──
        $panel
            ->discoverResources(
                in: __DIR__ . '/Filament/Admin/Resources',
                for: 'Taba\\Crm\\Filament\\Admin\\Resources'
            )
            ->discoverPages(
                in: __DIR__ . '/Filament/Admin/Pages',
                for: 'Taba\\Crm\\Filament\\Admin\\Pages'
            )
            ->discoverWidgets(
                in: __DIR__ . '/Filament/Admin/Widgets',
                for: 'Taba\\Crm\\Filament\\Admin\\Widgets'
            )
            ->pages([
                \Filament\Pages\Dashboard::class,
            ]);

        // ── Third-party plugins ──
        $panel->plugins([
            BreezyCore::make()
                ->myProfile(
                    shouldRegisterUserMenu: false,
                    shouldRegisterNavigation: true,
                    hasAvatars: true,
                )
                ->avatarUploadComponent(fn ($fileUpload) => $fileUpload->disableLabel())
                ->enableTwoFactorAuthentication(),

            FilamentShieldPlugin::make()
                ->gridColumns(['default' => 1, 'sm' => 2, 'lg' => 3])
                ->sectionColumnSpan(1)
                ->checkboxListColumns(['default' => 1, 'sm' => 2, 'lg' => 4])
                ->resourceCheckboxListColumns(['default' => 1, 'sm' => 2]),

            CuratorPlugin::make(__('Media'))
                ->pluralLabel(__('Media'))
                ->label(__('Media'))
                ->navigationIcon('heroicon-o-photo')
                ->navigationSort(3)
                ->navigationGroup(__('Content'))
                ->showBadge(),

            FilamentPeekPlugin::make(),

            AuthUIEnhancerPlugin::make()
                ->showEmptyPanelOnMobile(true)
                ->formPanelPosition('left')
                ->formPanelWidth('40%')
                ->mobileFormPanelPosition('bottom')
                ->emptyPanelBackgroundImageOpacity('70%')
                ->emptyPanelView('crm::filament.login-slideshow'),
        ]);

        $panel->navigationGroups([
            \Filament\Navigation\NavigationGroup::make(__('Content'))->icon('heroicon-o-book-open'),
            \Filament\Navigation\NavigationGroup::make(__('Communication'))->icon('heroicon-o-envelope'),
            \Filament\Navigation\NavigationGroup::make(__('Finance'))->icon('heroicon-o-currency-dollar'),
            \Filament\Navigation\NavigationGroup::make(__('AI Tools'))->icon('heroicon-o-sparkles'),
            \Filament\Navigation\NavigationGroup::make(__('Administration'))->icon('heroicon-o-shield-check'),
            \Filament\Navigation\NavigationGroup::make(__('Settings'))->icon('heroicon-o-cog-6-tooth')->collapsed(),
        ]);

        $panel->middleware([
            \Taba\Crm\Http\Middleware\ApplyBrandColors::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        $this->applyBrandTheme($panel);
        $this->registerAuthLayoutFix();
        $this->registerAdminCss();
    }

    // ── Theme & Colors ──

    protected function resolveColors(): array
    {
        try {
            $primary  = \Taba\Crm\Models\CrmSetting::get('crm_brand_primary_color', config('crm.brand.primary_color', '#0ea5e9'));
            $grayName = \Taba\Crm\Models\CrmSetting::get('crm_brand_gray_palette', config('crm.brand.gray_palette', 'Slate'));
        } catch (\Throwable) {
            $primary  = config('crm.brand.primary_color', '#0ea5e9');
            $grayName = config('crm.brand.gray_palette', 'Slate');
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
        $const = ucfirst(strtolower($name));

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
            // DB not yet migrated — skip silently
        }
    }

    // ── CSS Injection ──

    protected function registerAuthLayoutFix(): void
    {
        \Filament\Support\Facades\FilamentView::registerRenderHook(
            \Filament\View\PanelsRenderHook::HEAD_END,
            fn () => '<style>
                .custom-auth-wrapper { display:flex; flex-direction:column; width:100%; min-height:100vh; }
                @media(min-width:1024px){ .custom-auth-wrapper { flex-direction:row-reverse; } }
                .custom-auth-empty-panel { position:relative; display:flex; flex-direction:column; flex-grow:1; justify-content:center; padding:0 1rem; background-color:var(--empty-panel-background-color,var(--primary-500)); min-height:200px; }
                .custom-auth-form-panel { display:flex; flex-direction:column; justify-content:center; width:100%; padding:3rem 1rem; background-color:var(--form-panel-background-color,transparent); box-sizing:border-box; overflow:hidden; }
                @media(min-width:640px){ .custom-auth-form-panel { padding-left:1.5rem; padding-right:1.5rem; } }
                @media(min-width:1024px){ .custom-auth-form-panel { width:var(--form-panel-width,40%)!important; flex-shrink:0; padding:3rem 5rem; } }
                @media(min-width:1280px){ .custom-auth-form-panel { padding-left:9rem; padding-right:9rem; } }
                .custom-auth-form-wrapper { margin:0 auto; width:100%; max-width:24rem; }
                .fi-sidebar-nav { scrollbar-width:none; -ms-overflow-style:none; }
                .fi-sidebar-nav::-webkit-scrollbar { display:none; }
            </style>'
        );
    }

    protected function registerAdminCss(): void
    {
        $manifestPath = public_path('build/manifest.json');

        if (!file_exists($manifestPath)) {
            return;
        }

        $manifest = json_decode(file_get_contents($manifestPath), true) ?? [];
        $cssKeys  = [
            'resources/css/admin.css',
            'vendor/taba/crm/src/resources/css/admin.css',
            'packages/taba/crm/src/resources/css/admin.css',
        ];

        foreach ($cssKeys as $key) {
            if (isset($manifest[$key]['file'])) {
                $assetUrl = asset('build/' . $manifest[$key]['file']);
                \Filament\Support\Facades\FilamentView::registerRenderHook(
                    \Filament\View\PanelsRenderHook::HEAD_END,
                    fn () => '<link rel="stylesheet" href="' . e($assetUrl) . '">',
                );
                return;
            }
        }
    }

    // ── Factory ──

    public static function make(): static
    {
        return app(static::class);
    }
}
