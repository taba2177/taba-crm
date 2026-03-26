<?php

namespace Taba\Crm\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Taba\Crm\Http\Resources\Api\CrmSettingResource;
use Taba\Crm\Models\CrmSetting;

class SettingApiController extends ApiController
{
    /**
     * Get all public settings, grouped by group.
     */
    public function index(Request $request): JsonResponse
    {
        $cacheTtl = config('crm.api.cache_ttl', 300);

        $data = Cache::remember('api_settings_' . md5($request->fullUrl()), $cacheTtl, function () use ($request) {
            $query = CrmSetting::query()->orderBy('order');

            if ($group = $request->input('group')) {
                $query->where('group', $group);
            }

            return $query->get();
        });

        return CrmSettingResource::collection($data)
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Get grouped settings as key-value pairs (optimized for frontend config).
     */
    public function grouped(): JsonResponse
    {
        $cacheTtl = config('crm.api.cache_ttl', 300);

        $data = Cache::remember('api_settings_grouped', $cacheTtl, function () {
            return CrmSetting::getAllGrouped();
        });

        return $this->success($data);
    }

    /**
     * Get a setting by key.
     */
    public function show(string $key): JsonResponse
    {
        $setting = CrmSetting::where('key', $key)->firstOrFail();

        return (new CrmSettingResource($setting))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Update or create a setting (authenticated, admin).
     */
    public function update(Request $request, string $key): JsonResponse
    {
        $request->validate([
            'value'           => ['required'],
            'type'            => ['nullable', 'string'],
            'group'           => ['nullable', 'string'],
            'is_translatable' => ['nullable', 'boolean'],
        ]);

        $setting = CrmSetting::set(
            $key,
            $request->input('value'),
            $request->input('type', 'text'),
            $request->input('group', 'general'),
            $request->boolean('is_translatable')
        );

        Cache::forget('api_settings_grouped');

        return $this->success(new CrmSettingResource($setting));
    }
}
