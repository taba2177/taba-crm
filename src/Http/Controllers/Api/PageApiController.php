<?php

namespace Taba\Crm\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Taba\Crm\Http\Requests\Api\StorePageRequest;
use Taba\Crm\Http\Requests\Api\UpdatePageRequest;
use Taba\Crm\Http\Resources\Api\PageResource;
use Taba\Crm\Models\Page;

class PageApiController extends ApiController
{
    /**
     * List all pages.
     */
    public function index(Request $request): JsonResponse
    {
        $cacheTtl = config('crm.api.cache_ttl', 300);

        $data = Cache::remember('api_pages_' . md5($request->fullUrl()), $cacheTtl, function () {
            return Page::orderBy('id', 'asc')->paginate($this->getPerPage());
        });

        return PageResource::collection($data)
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Get a single page by slug.
     */
    public function show(string $slug): JsonResponse
    {
        $cacheTtl = config('crm.api.cache_ttl', 300);

        $page = Cache::remember('api_page_' . $slug, $cacheTtl, function () use ($slug) {
            return Page::where('slug', $slug)->firstOrFail();
        });

        return (new PageResource($page))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Create a new page (authenticated).
     */
    public function store(StorePageRequest $request): JsonResponse
    {
        $page = Page::create($request->validated());

        return $this->created(new PageResource($page));
    }

    /**
     * Update a page (authenticated).
     */
    public function update(UpdatePageRequest $request, Page $page): JsonResponse
    {
        $page->update($request->validated());

        return $this->success(new PageResource($page));
    }

    /**
     * Delete a page (authenticated).
     */
    public function destroy(Page $page): JsonResponse
    {
        $page->delete();

        return $this->success(null, 'Page deleted successfully');
    }
}
