<?php

namespace Taba\Crm\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Taba\Crm\Http\Requests\Api\StoreCategoryRequest;
use Taba\Crm\Http\Requests\Api\UpdateCategoryRequest;
use Taba\Crm\Http\Resources\Api\PostCategoryResource;
use Taba\Crm\Models\PostCategory;

class CategoryApiController extends ApiController
{
    /**
     * List all categories with optional filters.
     *
     * Query params: parent_only, active, header, include, with_count
     */
    public function index(Request $request): JsonResponse
    {
        $cacheKey = 'api_categories_' . md5($request->fullUrl());
        $cacheTtl = config('crm.api.cache_ttl', 300);

        $data = Cache::remember($cacheKey, $cacheTtl, function () use ($request) {
            $query = PostCategory::query();

            // Eager load relationships
            $includes = $this->parseIncludes($request, []);
            if (!empty($includes)) {
                $query->with($includes);
            }

            // Filter: only parent categories
            if ($request->boolean('parent_only')) {
                $query->parentOnly();
            }

            // Filter: active only
            if ($request->has('active')) {
                $query->where('is_active', $request->boolean('active'));
            }

            // Filter: registered in header
            if ($request->boolean('header')) {
                $query->where('register_in_header', true);
            }

            // Include posts count
            if ($request->boolean('with_count')) {
                $query->withCount('posts');
            }

            $query->orderBy('order', 'asc');

            // Return all or paginate
            if ($request->boolean('all')) {
                return $query->get();
            }

            return $query->paginate($this->getPerPage());
        });

        return PostCategoryResource::collection($data)
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Get a single category by slug with its posts.
     */
    public function show(Request $request, string $slug): JsonResponse
    {
        $cacheKey = 'api_category_' . $slug . '_' . md5($request->fullUrl());
        $cacheTtl = config('crm.api.cache_ttl', 300);

        $category = Cache::remember($cacheKey, $cacheTtl, function () use ($request, $slug) {
            return PostCategory::where('slug', $slug)
                ->with($this->parseIncludes($request, ['children']))
                ->withCount('posts')
                ->firstOrFail();
        });

        return (new PostCategoryResource($category))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Get posts for a specific category (paginated).
     */
    public function posts(Request $request, string $slug): JsonResponse
    {
        $category = PostCategory::where('slug', $slug)->firstOrFail();

        $posts = $category->posts()
            ->published()
            ->with(['postCategory', 'user', 'tags', 'image'])
            ->orderBy($request->input('sort_by', 'order'), $request->input('sort_dir', 'asc'))
            ->paginate($this->getPerPage());

        return \Taba\Crm\Http\Resources\Api\PostResource::collection($posts)
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Create a new category (authenticated).
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = PostCategory::create($request->validated());

        return $this->created(new PostCategoryResource($category));
    }

    /**
     * Update a category (authenticated).
     */
    public function update(UpdateCategoryRequest $request, PostCategory $category): JsonResponse
    {
        $category->update($request->validated());

        return $this->success(new PostCategoryResource($category));
    }

    /**
     * Delete a category (authenticated).
     */
    public function destroy(PostCategory $category): JsonResponse
    {
        if ($category->posts()->count() > 0) {
            return $this->error('Cannot delete category with existing posts', 422);
        }

        $category->delete();

        return $this->success(null, 'Category deleted successfully');
    }

    /**
     * Parse the ?include= query parameter.
     */
    private function parseIncludes(Request $request, array $default = []): array
    {
        if (!$request->has('include')) {
            return $default;
        }

        $allowed   = ['children', 'parent', 'posts', 'firstPost'];
        $requested = explode(',', $request->input('include'));

        return collect($requested)
            ->map(fn ($r) => trim($r))
            ->filter(fn ($r) => in_array($r, $allowed))
            ->values()
            ->toArray();
    }
}
