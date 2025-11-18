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
