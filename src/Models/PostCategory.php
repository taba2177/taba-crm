<?php

namespace Taba\Crm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;
use Taba\Crm\Filament\Admin\Resources\PostCategoryResource;
use Taba\Crm\Models\Post;
use Illuminate\Database\Eloquent\Relations\HasOne;


class PostCategory extends Model
{
    use HasFactory, HasTranslations;

    protected static function newFactory()
    {
        return \Taba\Crm\Database\Factories\PostCategoryFactory::new();
    }

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'order',
        'register_in_header',
        'description',
        'subtitle',
        "HEAVY_SECTION",
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
     * Retrieve the post edit URL.
     *
     * @return string
     */
    public function getEditUrlAttribute()
    {
        return PostCategoryResource::getUrl('edit', ['record' => $this]);
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

    /**
     * Retrieve only parent categories (no children).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeParentOnly($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Get the first post in the defined order shown on the homepage.
     */
    public function firstPost(): HasOne
    {
        return $this->hasOne(Post::class, 'post_category_id')
                    ->where("show_in_home", true)
                    ->published()
                    ->orderBy('order', 'asc');
    }
}
