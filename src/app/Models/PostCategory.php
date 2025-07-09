<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class PostCategory extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'name',
        'slug',
        'order',
        'register_in_header',
        'description',
        'subtitle',
        'section_component',
        'image',
        'is_active',
    ];

    public $translatable = [
        'name',
        'description',
        'subtitle',
    ];

    protected $casts = [
        'name',
        'description',
        'subtitle',
    ];


    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function parent()
    {
        return $this->belongsTo(PostCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(PostCategory::class, 'parent_id');
    }

        /**
        * Retrieve the published posts.
        *
        * @param \Illuminate\Database\Eloquent\Builder $query
        * @return \Illuminate\Database\Eloquent\Builder
        */
        public function scopeRegisterInHeader($query)
        {
        return $query->where('register_in_header', true)->get();
    }
}