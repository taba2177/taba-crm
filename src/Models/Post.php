<?php

namespace Taba\Crm\Models;

use Taba\Crm\Filament\Resources\PostResource;
use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;
use Taba\Crm\Models\PostCategory;
use Taba\Crm\Models\Tag;
use Taba\Crm\Models\User;



class Post extends Model
{
    use HasFactory;
    use HasTranslations;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'meta_title',
        'meta_description',
        'image_id',
        'images',
        'user_id',
        'post_category_id',
        'metadata', // Add this for custom fields
        'is_published',
        'published_at',
        'homepage_section_component',
        'homepage_section_content',
    ];

    public $translatable = [
        'title',
        'content',
        'metadata',
        'meta_title',
        'meta_description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'title' => 'array',
        'content' => 'array',
        'images' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'metadata' => 'array',
        'metadata.nested' => 'array',
        'homepage_section_content' => 'array',
    ];

    //relatedPosts
    public function relatedPosts()
    {
        return Post::where('id', '!=', $this->id)
            ->where('is_published', true)
            ->orderBy('published_at', 'desc');
    }


    /**
     * Get the user that owns the post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the featured image for the post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function image()
    {
        return $this->belongsTo(Media::class);
    }
    /**
     * Get the featured image for the post.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function images()
    {
        return $this->belongsToMany(Media::class);
    }

    public function getImagesAttribute($value)
    {
        $imageIds = json_decode($value, true);

        if (is_array($imageIds) && !empty($imageIds)) {
            return \Awcodes\Curator\Models\Media::whereIn('id', $imageIds)->get();
        }

        return collect();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getRoutePrefix()
    {
        return $this->postCategory->slug;
    }

    /**
     * Retrieve the post URL.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        if (request()->has('_filament')) {
            return route('preview.post', ['post' => $this, 'data' => request()->all()]);
        }

        $category = $this->postCategory->slug;

        $categories = PostCategory::select('slug')->pluck('slug')->toArray();

        if (in_array($category, $categories)) {
            return route('posts.show', [
                'category' => $category,
                'post' => $this
            ]);
        }

        return route('posts.show', $this);
    }


    // public function getRandomImage()
    // {
    //     $numbers = array_merge(range(1, 9), [20, 60]);
    //     $randomIndex = array_rand($numbers);
    //     $randomId = $numbers[$randomIndex];
    //     return "https://picsum.photos/id/{$randomId}/350/400";
    // }

    public function getRandomImage()
    {
        $numbers = array_merge(range(1, 9), [20, 60]);
        $cacheKey = 'random_image_' . $this->id;

        if (cache()->has($cacheKey)) {
            return cache()->get($cacheKey);
        }

        $randomIndex = array_rand($numbers);
        $randomId = $numbers[$randomIndex];
        $imageUrl = "https://picsum.photos/id/{$randomId}/500/500";

        cache()->rememberForever($cacheKey, function () use ($imageUrl) {
            return $imageUrl;
        });

        return $imageUrl;
    }


    public function getContentTypeAttribute()
    {
        return $this->postCategory->slug;
    }

    public function getMeta($key, $default = null)
    {
        return Arr::get($this->metadata ?? [], $key, $default);
    }

    public function scopeForCategory($query, $categorySlug)
    {
        return $query->whereHas('postCategory', fn($q) => $q->where('slug', $categorySlug));
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Retrieve the post edit URL.
     *
     * @return string
     */
    public function getEditUrlAttribute()
    {
        return PostResource::getUrl('edit', ['record' => $this]);
    }

    /**
     * Retrieve the post content blocks as an object.
     *
     * @return object
     */
    public function getBlocksAttribute()
    {
        return json_decode(json_encode($this->content ?? []));
    }

    /**
     * Retrieve the post excerpt.
     *
     * @return string
     */
    public function getExcerptAttribute()
    {
        $excerpt = collect($this->content)
            ->where('type', 'markdown')
            ->first() ?? [];

        $excerpt = collect(
            explode("\n", Arr::get($excerpt, 'data.content', ''))
        )->first();

        return Str::limit($excerpt, 160);
    }

    /**
     * Retrieve the published posts.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Retrieve the draft posts.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDrafts($query)
    {
        return $query->where('is_published', false);
    }

    public function postCategory()
    {
        return $this->belongsTo(PostCategory::class);
    }
}
