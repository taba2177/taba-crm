<?php

if (!function_exists('crm_setting')) {
    /**
     * Get a CRM setting value with fallback to config
     *
     * @param string $key The setting key (e.g., 'contact.phone' or 'crm_contact_phone')
     * @param mixed $default Default value if setting not found
     * @return mixed
     */
    function crm_setting(string $key, $default = null)
    {
        // Normalize key format
        $dbKey = str_starts_with($key, 'crm_') ? $key : 'crm_' . str_replace('.', '_', $key);

        try {
            $setting = \Taba\Crm\Models\CrmSetting::where('key', $dbKey)->first();

            if ($setting) {
                // Handle translatable values
                if ($setting->is_translatable && is_array($setting->value)) {
                    $locale = app()->getLocale();
                    return $setting->value[$locale] ?? $setting->value['en'] ?? $default;
                }

                return $setting->value ?? $default;
            }
        } catch (\Exception $e) {
            // Database might not be set up yet, fall through to config
        }

        // Fallback to config
        $configKey = str_replace('_', '.', str_replace('crm_', 'crm.', $dbKey));
        return config($configKey, $default);
    }
}

if (!function_exists('crm_contact')) {
    /**
     * Get contact information
     *
     * @param string|null $field Specific field to get (phone, email, address, etc.)
     * @return mixed
     */
    function crm_contact(?string $field = null)
    {
        if ($field === null) {
            return [
                'phone' => crm_setting('contact.phone'),
                'email' => crm_setting('contact.email'),
                'address' => crm_setting('contact.address'),
                'city' => crm_setting('contact.city'),
                'postal_code' => crm_setting('contact.postal_code'),
                'latitude' => crm_setting('contact.latitude'),
                'longitude' => crm_setting('contact.longitude'),
            ];
        }

        return crm_setting("contact.{$field}");
    }
}

if (!function_exists('crm_social_links')) {
    /**
     * Get all social media links as array
     *
     * @return array
     */
    function crm_social_links(): array
    {
        $links = [];

        $platforms = ['facebook', 'twitter', 'instagram', 'linkedin', 'youtube'];

        foreach ($platforms as $platform) {
            $url = crm_setting("contact.{$platform}");
            if (!empty($url)) {
                $links[] = $url;
            }
        }

        return $links;
    }
}

if (!function_exists('crm_business')) {
    /**
     * Get business information
     *
     * @param string|null $field Specific field to get (name, price_range, opens, closes)
     * @return mixed
     */
    function crm_business(?string $field = null)
    {
        if ($field === null) {
            return [
                'name' => crm_setting('business.name', config('app.name')),
                'price_range' => crm_setting('business.price_range'),
                'opens' => crm_setting('business.opens', '09:00'),
                'closes' => crm_setting('business.closes', '18:00'),
            ];
        }

        if ($field === 'name') {
            return crm_setting('business.name', config('app.name'));
        }

        return crm_setting("business.{$field}");
    }
}

if (!function_exists('crm_hex_to_rgb')) {
    /**
     * Convert a hex color to comma-separated RGB values for CSS custom properties.
     *
     * @param string $hex e.g. "#3baac5" or "3baac5"
     * @return string e.g. "59, 170, 197"
     */
    function crm_hex_to_rgb(string $hex): string
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        return "{$r}, {$g}, {$b}";
    }
}

if (!function_exists('crm_theme_css')) {
    /**
     * Generate inline CSS custom properties from CRM theme settings.
     * Used in the layout <head> to make frontend colors configurable from the dashboard.
     *
     * @return string CSS <style> block or empty string if no theme set
     */
    function crm_theme_css(): string
    {
        try {
            $vars = [];

            $map = [
                'crm_theme_primary_color'       => '--crm-primary',
                'crm_theme_primary_light_color'  => '--crm-primary-light',
                'crm_theme_secondary_color'      => '--crm-secondary',
            ];

            foreach ($map as $settingKey => $cssVar) {
                $hex = crm_setting($settingKey);
                if (!empty($hex) && preg_match('/^#?[0-9a-fA-F]{3,6}$/', $hex)) {
                    $vars[] = "{$cssVar}: " . crm_hex_to_rgb($hex);
                }
            }

            if (empty($vars)) {
                return '';
            }

            $css = implode("; ", $vars);

            // Font override
            $font = crm_setting('crm_theme_font_family');
            if (!empty($font)) {
                $css .= "; --crm-font-family: '{$font}', sans-serif";
            }

            return "<style>:root { {$css}; }</style>";
        } catch (\Throwable) {
            return '';
        }
    }
}
