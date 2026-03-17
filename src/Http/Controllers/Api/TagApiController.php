<?php

namespace Taba\Crm\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Taba\Crm\Http\Resources\Api\TagResource;
use Taba\Crm\Models\Tag;

class TagApiController extends ApiController
{
    /**
     * List all tags.
     */
    public function index(Request $request): JsonResponse
    {
        $cacheTtl = config('crm.api.cache_ttl', 300);

        $data = Cache::remember('api_tags_' . md5($request->fullUrl()), $cacheTtl, function () use ($request) {
            $query = Tag::query();

            if ($request->boolean('with_count')) {
                $query->withCount('posts');
            }

            if ($search = $request->input('search')) {
                $query->where('name', 'LIKE', "%{$search}%");
            }

            if ($request->boolean('all')) {
                return $query->orderBy('name')->get();
            }

            return $query->orderBy('name')->paginate($this->getPerPage());
        });

        return TagResource::collection($data)
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Get a single tag by slug with its posts.
     */
    public function show(Request $request, string $slug): JsonResponse
    {
        $tag = Tag::where('slug', $slug)
            ->withCount('posts')
            ->firstOrFail();

        return (new TagResource($tag))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Get posts for a specific tag.
     */
    public function posts(Request $request, string $slug): JsonResponse
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        $posts = $tag->posts()
            ->published()
            ->with(['postCategory', 'user', 'tags', 'image'])
            ->orderBy('published_at', 'desc')
            ->paginate($this->getPerPage());

        return \Taba\Crm\Http\Resources\Api\PostResource::collection($posts)
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Create a tag (authenticated).
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name'  => ['required', 'array'],
            'name.*' => ['required', 'string', 'max:255'],
            'slug'  => ['nullable', 'string', 'max:255', 'unique:tags,slug'],
        ]);

        $tag = Tag::create($request->only(['name', 'slug']));

        return $this->created(new TagResource($tag));
    }

    /**
     * Update a tag (authenticated).
     */
    public function update(Request $request, Tag $tag): JsonResponse
    {
        $request->validate([
            'name'   => ['sometimes', 'array'],
            'name.*' => ['required', 'string', 'max:255'],
            'slug'   => ['sometimes', 'string', 'max:255', 'unique:tags,slug,' . $tag->id],
        ]);

        $tag->update($request->only(['name', 'slug']));

        return $this->success(new TagResource($tag));
    }

    /**
     * Delete a tag (authenticated).
     */
    public function destroy(Tag $tag): JsonResponse
    {
        $tag->posts()->detach();
        $tag->delete();

        return $this->success(null, 'Tag deleted successfully');
    }
}
