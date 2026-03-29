<?php

namespace Taba\Crm\Http\Controllers\Api\V2;

use Illuminate\Http\JsonResponse;
use Taba\Crm\Components\Contracts\SectionLayout;
use Taba\Crm\Components\Registry\ComponentRegistry;
use Taba\Crm\Http\Controllers\Api\ApiController;

class ComponentController extends ApiController
{
    /**
     * List all available component types.
     * GET /api/v2/components
     */
    public function index(): JsonResponse
    {
        $components = ComponentRegistry::all()->map(fn ($component) => [
            'key' => $component->key(),
            'label' => $component->label(),
            'icon' => $component->icon(),
            'description' => $component->description(),
            'layout' => $component->layout()->value,
            'max_items' => $component->maxItems(),
        ])->values();

        return $this->success($components);
    }
}
