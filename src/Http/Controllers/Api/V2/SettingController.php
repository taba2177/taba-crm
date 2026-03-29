<?php

namespace Taba\Crm\Http\Controllers\Api\V2;

use Illuminate\Http\JsonResponse;
use Taba\Crm\Http\Controllers\Api\ApiController;
use Taba\Crm\Models\CrmSetting;

class SettingController extends ApiController
{
    /**
     * Get all settings grouped.
     * GET /api/v2/settings
     */
    public function index(): JsonResponse
    {
        return $this->success(CrmSetting::getAllGrouped());
    }
}
