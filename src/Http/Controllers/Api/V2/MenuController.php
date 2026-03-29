<?php

namespace Taba\Crm\Http\Controllers\Api\V2;

use Illuminate\Http\JsonResponse;
use Taba\Crm\Http\Controllers\Api\ApiController;
use Taba\Crm\Models\Menu;

class MenuController extends ApiController
{
    /**
     * List all menus.
     * GET /api/v2/menus
     */
    public function index(): JsonResponse
    {
        $menus = Menu::all()->map(fn ($menu) => [
            'id' => $menu->id,
            'name' => $menu->name,
            'group' => $menu->group,
            'items' => $menu->items,
        ]);

        return $this->success($menus);
    }
}
