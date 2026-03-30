<?php

namespace Taba\Crm\Concerns;

use Taba\Crm\Models\CrmSetting;

trait HasNavigationVisibility
{
    /**
     * A unique slug identifying this nav item (e.g. 'contact-entries').
     * Override in your resource if the auto-generated slug doesn't match.
     */
    public static function getNavSlug(): string
    {
        return str(class_basename(static::class))
            ->beforeLast('Resource')
            ->kebab()
            ->plural()
            ->toString();
    }

    public static function shouldRegisterNavigation(): bool
    {
        $slug = static::getNavSlug();

        // 1. Super-admin explicitly hid it
        $hidden = static::getHiddenNavItems();
        if (in_array($slug, $hidden)) {
            return false;
        }

        // 2. Super-admin explicitly force-showed it
        $forced = static::getForceShownNavItems();
        if (in_array($slug, $forced)) {
            return true;
        }

        // 3. Auto-hide if configured and empty
        $autoHide = config('crm.navigation.auto_hide_empty', []);
        if (in_array($slug, $autoHide) && static::getModel()::count() === 0) {
            return false;
        }

        return true;
    }

    protected static function getHiddenNavItems(): array
    {
        try {
            $val = CrmSetting::get('crm_nav_hidden_items', []);
            return is_array($val) ? $val : [];
        } catch (\Throwable) {
            return [];
        }
    }

    protected static function getForceShownNavItems(): array
    {
        try {
            $val = CrmSetting::get('crm_nav_force_shown_items', []);
            return is_array($val) ? $val : [];
        } catch (\Throwable) {
            return [];
        }
    }
}
