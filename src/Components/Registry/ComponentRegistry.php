<?php

namespace Taba\Crm\Components\Registry;

use Illuminate\Support\Collection;
use InvalidArgumentException;
use Taba\Crm\Components\Contracts\SectionComponent;

class ComponentRegistry
{
    /** @var array<string, SectionComponent> */
    protected static array $components = [];

    public static function register(string $componentClass): void
    {
        $instance = app($componentClass);
        static::registerInstance($instance);
    }

    public static function registerInstance(SectionComponent $component): void
    {
        static::$components[$component->key()] = $component;
    }

    public static function discoverIn(string $directory, string $namespace): void
    {
        if (!is_dir($directory)) {
            return;
        }

        foreach (glob($directory . '/*.php') as $file) {
            $className = $namespace . '\\' . basename($file, '.php');
            if (class_exists($className) && is_subclass_of($className, SectionComponent::class)) {
                static::register($className);
            }
        }
    }

    public static function fromConfig(array $components): void
    {
        foreach ($components as $componentClass) {
            if (is_string($componentClass) && class_exists($componentClass)) {
                static::register($componentClass);
            }
        }
    }

    public static function resolve(string $key): SectionComponent
    {
        if (!isset(static::$components[$key])) {
            throw new InvalidArgumentException("Section component not found: {$key}");
        }

        return static::$components[$key];
    }

    public static function all(): Collection
    {
        return collect(static::$components);
    }

    public static function forSelect(): array
    {
        $locale = app()->getLocale();
        $map = [];
        foreach (static::$components as $key => $component) {
            $map[$key] = $component->label()[$locale] ?? $component->label()['ar'] ?? $key;
        }
        return $map;
    }

    public static function has(string $key): bool
    {
        return isset(static::$components[$key]);
    }

    public static function keys(): array
    {
        return array_keys(static::$components);
    }

    public static function flush(): void
    {
        static::$components = [];
    }

    public static function frontendSections(): array
    {
        $locale = app()->getLocale();
        $sections = config('crm.frontend_sections', []);
        $map = [];
        foreach ($sections as $key => $meta) {
            $map[$key] = $meta['label'][$locale] ?? $meta['label']['ar'] ?? $key;
        }
        return $map;
    }

    public static function frontendSectionIcons(): array
    {
        $sections = config('crm.frontend_sections', []);
        $icons = [];
        foreach ($sections as $key => $meta) {
            $icons[$key] = $meta['icon'] ?? 'heroicon-o-document-text';
        }
        return $icons;
    }
}
