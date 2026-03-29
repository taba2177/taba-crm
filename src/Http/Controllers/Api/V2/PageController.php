<?php

namespace Taba\Crm\Http\Controllers\Api\V2;

use Illuminate\Http\JsonResponse;
use Taba\Crm\Http\Controllers\Api\ApiController;
use Taba\Crm\Models\Page;

class PageController extends ApiController
{
    /**
     * Show a page by slug.
     * GET /api/v2/pages/{slug}
     */
    public function show(string $slug): JsonResponse
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        return $this->success([
            'id' => $page->id,
            'title' => $page->title,
            'slug' => $page->slug,
            'content' => $page->content,
        ]);
    }
}
