<?php

namespace Taba\Crm\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Taba\Crm\Http\Resources\Api\MenuResource;
use Taba\Crm\Http\Resources\Api\PostCategoryResource;
use Taba\Crm\Http\Resources\Api\PostResource;
use Taba\Crm\Http\Resources\Api\ReviewResource;
use Taba\Crm\Models\CrmSetting;
use Taba\Crm\Models\Menu;
use Taba\Crm\Models\Post;
use Taba\Crm\Models\PostCategory;
use Taba\Crm\Models\Review;

class HomeApiController extends ApiController
{
    /**
     * Bootstrap endpoint: returns all data needed for the Angular app to initialize.
     * Menus, navigation categories, site settings — all in one request.
     *
     * GET /api/v1/init
     */
    public function init(Request $request): JsonResponse
    {
        $cacheTtl = config('crm.api.cache_ttl', 300);

        $data = Cache::remember('api_init_' . app()->getLocale(), $cacheTtl, function () {
            return [
                'settings'   => CrmSetting::getAllGrouped(),
                'menus'      => MenuResource::collection(Menu::all()),
                'navigation' => PostCategoryResource::collection(
                    PostCategory::where('register_in_header', true)
                        ->orderBy('order')
                        ->get()
                ),
                'locale'     => app()->getLocale(),
                'locales'    => config('crm.available_locales', ['ar', 'en']),
                'app_name'   => config('app.name'),
            ];
        });

        return $this->success($data);
    }

    /**
     * Homepage data: featured posts grouped by category sections.
     *
     * GET /api/v1/home
     */
    public function home(Request $request): JsonResponse
    {
        $cacheTtl = config('crm.api.cache_ttl', 300);

        $data = Cache::remember('api_home_' . app()->getLocale(), $cacheTtl, function () {
            // Get categories that have homepage sections
            $categories = PostCategory::where('register_in_header', true)
                ->with(['firstPost', 'children'])
                ->withCount('posts')
                ->orderBy('order')
                ->get();

            // Get featured/homepage posts
            $homePosts = Post::published()
                ->where('show_in_home', true)
                ->with(['postCategory', 'image', 'tags'])
                ->orderBy('order')
                ->get();

            // Get latest reviews
            $reviews = Review::orderBy('created_at', 'desc')
                ->take(10)
                ->get();

            return [
                'categories'     => PostCategoryResource::collection($categories),
                'featured_posts' => PostResource::collection($homePosts),
                'reviews'        => ReviewResource::collection($reviews),
            ];
        });

        return $this->success($data);
    }
}
