<?php

namespace Taba\Crm\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Taba\Crm\Traits\FilamentTranslatable;

class CrmSetting extends Model
{
    use HasTranslations, FilamentTranslatable;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
        'is_translatable',
        'order',
    ];

    protected $casts = [
        'value' => 'json',
        'is_translatable' => 'boolean',
    ];

    public $translatable = ['label', 'description'];

    /**
     * Get a setting value by key with fallback to config
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();

        if (!$setting) {
            // Fallback to config
            return config(str_replace('_', '.', $key), $default);
        }

        // Handle translatable values
        if ($setting->is_translatable && is_array($setting->value)) {
            $locale = app()->getLocale();
            return $setting->value[$locale] ?? $setting->value['en'] ?? $default;
        }

        return $setting->value ?? $default;
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, $value, string $type = 'text', string $group = 'general', bool $isTranslatable = false): self
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
                'is_translatable' => $isTranslatable,
            ]
        );
    }

    /**
     * Get all settings grouped by group
     */
    public static function getAllGrouped(): array
    {
        return static::orderBy('order')
            ->get()
            ->groupBy('group')
            ->map(function ($settings) {
                return $settings->mapWithKeys(function ($setting) {
                    return [$setting->key => $setting->value];
                });
            })
            ->toArray();
    }

    /**
     * Get all settings as a flat key→value map (used by Angular ContentService)
     */
    public static function getAllFlat(): array
    {
        return static::orderBy('order')
            ->get()
            ->mapWithKeys(fn ($s) => [$s->key => $s->value])
            ->toArray();
    }
}
