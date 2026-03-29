<?php

namespace Taba\Crm;

use Filament\Contracts\Plugin;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Taba\Crm\Components\Contracts\SectionLayout;
use Taba\Crm\Components\Registry\ComponentRegistry;
use Taba\Crm\Models\PostCategory;

class CrmClientPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'taba-crm-client';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->profile()
            ->discoverResources(
                in: __DIR__ . '/Filament/Client/Resources',
                for: 'Taba\\Crm\\Filament\\Client\\Resources'
            )
            ->discoverPages(
                in: __DIR__ . '/Filament/Client/Pages',
                for: 'Taba\\Crm\\Filament\\Client\\Pages'
            )
            ->discoverWidgets(
                in: __DIR__ . '/Filament/Client/Widgets',
                for: 'Taba\\Crm\\Filament\\Client\\Widgets'
            );
    }

    public function boot(Panel $panel): void
    {
        // Register dynamic navigation from PostCategories
        $panel->navigationItems($this->buildCategoryNavigation($panel));
    }

    protected function buildCategoryNavigation(Panel $panel): array
    {
        $items = [];
        $panelId = $panel->getId();

        try {
            $categories = PostCategory::query()
                ->whereNotNull('section_component')
                ->where('is_active', true)
                ->orderBy('order')
                ->withCount('posts')
                ->get();
        } catch (\Throwable) {
            // Table may not exist yet (e.g., during migrations)
            return [];
        }

        foreach ($categories as $category) {
            $component = ComponentRegistry::has($category->section_component)
                ? ComponentRegistry::resolve($category->section_component)
                : null;

            $icon = $component?->icon() ?? 'heroicon-o-rectangle-stack';
            $layout = $component?->layout();

            // For SINGLE layout or single-item LIST: link directly to edit
            // For LIST layout with multiple items: link to section resource list
            $isSingleEntry = $layout === SectionLayout::SINGLE
                || ($layout === SectionLayout::LIST && $category->posts_count <= 1);

            if ($isSingleEntry) {
                $url = route("filament.{$panelId}.pages.edit-section", ['record' => $category->id]);
            } else {
                $url = route("filament.{$panelId}.resources.section-posts.index", ['category' => $category->id]);
            }

            $items[] = NavigationItem::make($category->name)
                ->icon($icon)
                ->group(__('أقسام الموقع'))
                ->sort($category->order + 10)
                ->url($url)
                ->isActiveWhen(fn () => request()->routeIs('*section*') && request()->route('category') == $category->id);
        }

        return $items;
    }
}
