<?php

namespace Taba\Crm\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Taba\Crm\Http\Resources\Api\MenuResource;
use Taba\Crm\Models\Menu;

class MenuApiController extends ApiController
{
    /**
     * List all menus, optionally filtered by group.
     */
    public function index(Request $request): JsonResponse
    {
        $cacheTtl = config('crm.api.cache_ttl', 300);

        $data = Cache::remember('api_menus_' . md5($request->fullUrl()), $cacheTtl, function () use ($request) {
            $query = Menu::query();

            if ($group = $request->input('group')) {
                $query->where('group', $group);
            }

            return $query->get();
        });

        return MenuResource::collection($data)
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Get a single menu by ID.
     */
    public function show(Menu $menu): JsonResponse
    {
        return (new MenuResource($menu))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Create a menu (authenticated).
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'group' => ['nullable', 'string', 'max:255'],
            'items' => ['required', 'array'],
        ]);

        $menu = Menu::create($request->only(['name', 'group', 'items']));

        return $this->created(new MenuResource($menu));
    }

    /**
     * Update a menu (authenticated).
     */
    public function update(Request $request, Menu $menu): JsonResponse
    {
        $request->validate([
            'name'  => ['sometimes', 'string', 'max:255'],
            'group' => ['nullable', 'string', 'max:255'],
            'items' => ['sometimes', 'array'],
        ]);

        $menu->update($request->only(['name', 'group', 'items']));

        return $this->success(new MenuResource($menu));
    }

    /**
     * Delete a menu (authenticated).
     */
    public function destroy(Menu $menu): JsonResponse
    {
        $menu->delete();

        return $this->success(null, 'Menu deleted successfully');
    }
}
