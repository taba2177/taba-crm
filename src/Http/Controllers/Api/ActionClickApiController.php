<?php

namespace Taba\Crm\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Taba\Crm\Models\ActionClick;

class ActionClickApiController extends ApiController
{
    public function store(Request $request): Response
    {
        $data = $request->validate([
            'action' => ['required', 'in:whatsapp,call,form'],
            'source' => ['nullable', 'string', 'max:50'],
            'page'   => ['nullable', 'string', 'max:255'],
        ]);

        ActionClick::create(array_merge($data, [
            'ip_hash' => hash_hmac('sha256', $request->ip() ?? '0.0.0.0', config('app.key')),
        ]));

        return response()->noContent();
    }

    public function summary(Request $request): JsonResponse
    {
        $byAction = ActionClick::selectRaw('action, count(*) as total')
            ->groupBy('action')
            ->pluck('total', 'action');

        $bySource = ActionClick::selectRaw('source, count(*) as total')
            ->whereNotNull('source')
            ->groupBy('source')
            ->pluck('total', 'source');

        $daily = ActionClick::selectRaw('DATE(created_at) as date, count(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return $this->success([
            'by_action' => $byAction,
            'by_source' => $bySource,
            'daily'     => $daily,
        ]);
    }
}
