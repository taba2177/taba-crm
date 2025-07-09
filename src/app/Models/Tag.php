<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Spatie\Translatable\HasTranslations;

class Tag extends Model
{
    use HasTranslations;

    protected $fillable = ['name', 'slug'];

    public $translatable = ['name'];

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    // Add validation before creating or updating a tag
    protected static function booted()
    {
        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = \Illuminate\Support\Str::slug($tag->getTranslation('name', 'en'));
            }
        });

        static::updating(function ($tag) {
            if ($tag->isDirty('name') && empty($tag->slug)) {
                $tag->slug = \Illuminate\Support\Str::slug($tag->getTranslation('name', 'en'));
            }
        });
    }
}