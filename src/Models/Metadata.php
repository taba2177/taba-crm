<?php

namespace Taba\Crm\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations as translatable;
use Taba\Crm\Traits\FilamentTranslatable;

class Metadata extends Model
{
    use translatable, FilamentTranslatable;

    protected $fillable = ['key', 'value'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];

    public $translatable = ['key', 'value'];

    // Helper method to get all unique keys
    // public static function getUniqueKeys()
    // {
    //     return static::all()
    //         ->mapWithKeys(function ($item) {
    //             return [$item->id => $item->getTranslation('key', app()->getLocale())];
    //         })
    //         ->toArray();
    // }

    // // Helper method to get values for a specific key
    // public static function getValuesForKey($id)
    // {
    //     $metadata = static::find($id);
    //     if (!$metadata) {
    //         return [];
    //     }
    //     return collect($metadata->getTranslation('value', app()->getLocale()))
    //         ->mapWithKeys(fn($item) => [$item => $item])
    //         ->toArray();
    // }
}