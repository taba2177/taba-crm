<?php

namespace Taba\Crm\Traits;

/**
 * Fixes Spatie HasTranslations for Filament v4 forms.
 *
 * Spatie's toArray() returns all locales as an array for each translatable
 * attribute, but Filament TextInput expects a plain string. This trait
 * overrides attributesToArray() to return the current-locale string.
 */
trait FilamentTranslatable
{
    public function attributesToArray(): array
    {
        $attributes = parent::attributesToArray();

        foreach ($this->getTranslatableAttributes() as $key) {
            if (array_key_exists($key, $attributes)) {
                $attributes[$key] = $this->getTranslation($key, $this->getLocale());
            }
        }

        return $attributes;
    }
}
