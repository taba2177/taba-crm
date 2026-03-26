<?php

namespace Taba\Crm\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Taba\Crm\Http\Requests\Api\StorePostRequest;
use Taba\Crm\Http\Requests\Api\UpdatePostRequest;
use Taba\Crm\Http\Resources\Api\PostResource;
use Taba\Crm\Models\Post;

class PostApiController extends ApiController
{
    /**
     * List published posts with filtering, sorting, and pagination.
     *
     * Query params: category, tag, search, sort_by, sort_dir, per_page, include
     */
    public function index(Request $request): JsonResponse
    {
        $cacheKey = 'api_posts_' . md5($request->fullUrl());
        $cacheTtl = config('crm.api.cache_ttl', 300);

        $data = Cache::remember($cacheKey, $cacheTtl, function () use ($request) {
            $query = Post::query()
                ->published()
                ->with($this->parseIncludes($request, ['postCategory', 'user', 'tags', 'image']));

            // Filter by category slug
            if ($slug = $request->input('category')) {
                $query->forCategory($slug);
            }

            // Filter by tag slug
            if ($tag = $request->input('tag')) {
                $query->whereHas('tags', fn ($q) => $q->where('slug', $tag));
            }

            // Full-text search on title
            if ($search = $request->input('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                      ->orWhere('content', 'LIKE', "%{$search}%");
                });
            }

            // Filter by show_in_home
            if ($request->has('show_in_home')) {
                $query->where('show_in_home', $request->boolean('show_in_home'));
            }

            // Sorting
            $sortBy  = $request->input('sort_by', 'published_at');
            $sortDir = $request->input('sort_dir', 'desc');
            $allowed = ['published_at', 'created_at', 'title', 'order', 'id'];

            if (in_array($sortBy, $allowed)) {
                $query->orderBy($sortBy, $sortDir === 'asc' ? 'asc' : 'desc');
            }

            return $query->paginate($this->getPerPage());
        });

        return PostResource::collection($data)
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Get a single published post by slug.
     */
    public function show(Request $request, string $slug): JsonResponse
    {
        $cacheKey = 'api_post_' . $slug . '_' . md5($request->fullUrl());
        $cacheTtl = config('crm.api.cache_ttl', 300);

        $post = Cache::remember($cacheKey, $cacheTtl, function () use ($request, $slug) {
            return Post::where('slug', $slug)
                ->published()
                ->with($this->parseIncludes($request, ['postCategory', 'user', 'tags', 'image']))
                ->firstOrFail();
        });

        return (new PostResource($post))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Create a new post (authenticated).
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        $post = Post::create(array_merge(
            $request->validated(),
            ['user_id' => $request->user()->id]
        ));

        if ($request->has('tags')) {
            $post->tags()->sync($request->input('tags'));
        }

        $this->clearPostCache();

        return $this->created(new PostResource($post->load('postCategory', 'user', 'tags', 'image')));
    }

    /**
     * Update an existing post (authenticated).
     */
    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        $post->update($request->validated());

        if ($request->has('tags')) {
            $post->tags()->sync($request->input('tags'));
        }

        $this->clearPostCache();

        return $this->success(new PostResource($post->load('postCategory', 'user', 'tags', 'image')));
    }

    /**
     * Delete a post (authenticated).
     */
    public function destroy(Post $post): JsonResponse
    {
        $post->tags()->detach();
        $post->delete();

        $this->clearPostCache();

        return $this->success(null, 'Post deleted successfully');
    }

    /**
     * Get related posts for a given post.
     */
    public function related(Request $request, string $slug): JsonResponse
    {
        $post = Post::where('slug', $slug)->published()->firstOrFail();

        $related = Post::published()
            ->forCategory($post->postCategory->slug)
            ->where('id', '!=', $post->id)
            ->with($this->parseIncludes($request, ['postCategory', 'image']))
            ->latest('published_at')
            ->take($request->input('limit', 5))
            ->get();

        return PostResource::collection($related)
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Parse the ?include= query parameter into allowed eager-load relationships.
     */
    private function parseIncludes(Request $request, array $default = []): array
    {
        if (!$request->has('include')) {
            return $default;
        }

        $allowed  = ['postCategory', 'user', 'tags', 'image', 'metadata'];
        $requested = explode(',', $request->input('include'));

        // Map Angular-friendly names to Eloquent relationships
        $map = [
            'category' => 'postCategory',
            'post_category' => 'postCategory',
        ];

        return collect($requested)
            ->map(fn ($r) => $map[trim($r)] ?? trim($r))
            ->filter(fn ($r) => in_array($r, $allowed))
            ->values()
            ->toArray();
    }

    /**
     * Clear post-related cache entries.
     */
    private function clearPostCache(): void
    {
        // If using tagged cache (Redis/Memcached), use tags
        if (method_exists(Cache::getStore(), 'tags')) {
            Cache::tags(['api_posts'])->flush();
        }
        // For file-based cache, we use a simple approach
        Cache::flush();
    }
}
