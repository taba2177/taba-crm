<?php

namespace Taba\Crm\Http\Controllers\Api\V2;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Taba\Crm\Components\Registry\ComponentRegistry;
use Taba\Crm\Http\Controllers\Api\ApiController;
use Taba\Crm\Models\PostCategory;

class SectionController extends ApiController
{
    /**
     * List all active sections with component-aware data.
     * GET /api/v2/sections
     */
    public function index(Request $request): JsonResponse
    {
        $sections = PostCategory::whereNotNull('section_component')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        $data = $sections->map(function (PostCategory $section) {
            if (ComponentRegistry::has($section->section_component)) {
                $component = ComponentRegistry::resolve($section->section_component);
                return $component->toApi($section);
            }

            return [
                'id' => $section->id,
                'component' => $section->section_component,
                'order' => $section->order,
                'is_active' => (bool) $section->is_active,
                'title' => $section->getTranslations('name'),
            ];
        })->values();

        return $this->success($data);
    }

    /**
     * Show a single section with full component data.
     * GET /api/v2/sections/{id}
     */
    public function show(int $id): JsonResponse
    {
        $section = PostCategory::whereNotNull('section_component')
            ->findOrFail($id);

        if (ComponentRegistry::has($section->section_component)) {
            $component = ComponentRegistry::resolve($section->section_component);
            return $this->success($component->toApi($section));
        }

        return $this->success([
            'id' => $section->id,
            'component' => $section->section_component,
            'order' => $section->order,
            'is_active' => (bool) $section->is_active,
            'title' => $section->getTranslations('name'),
        ]);
    }

    /**
     * Create a new section (admin).
     * POST /api/v2/admin/sections
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|array',
            'name.ar' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:post_categories,slug',
            'section_component' => 'required|string',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if (!ComponentRegistry::has($validated['section_component'])) {
            return $this->error('Invalid section component type.', 422);
        }

        $section = PostCategory::create(array_merge($validated, [
            'is_active' => $validated['is_active'] ?? true,
            'order' => $validated['order'] ?? 0,
        ]));

        return $this->created([
            'id' => $section->id,
            'component' => $section->section_component,
        ]);
    }

    /**
     * Update a section (admin).
     * PUT /api/v2/admin/sections/{id}
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $section = PostCategory::whereNotNull('section_component')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'nullable|array',
            'slug' => 'nullable|string|max:255|unique:post_categories,slug,' . $section->id,
            'section_component' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if (isset($validated['section_component']) && !ComponentRegistry::has($validated['section_component'])) {
            return $this->error('Invalid section component type.', 422);
        }

        $section->update($validated);

        return $this->success([
            'id' => $section->id,
            'component' => $section->section_component,
        ]);
    }

    /**
     * Delete a section (admin).
     * DELETE /api/v2/admin/sections/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        $section = PostCategory::whereNotNull('section_component')->findOrFail($id);
        $section->update(['is_active' => false]);

        return $this->success(null, 'Section deactivated successfully');
    }
}
