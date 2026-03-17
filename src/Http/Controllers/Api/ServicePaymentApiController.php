<?php

namespace Taba\Crm\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Taba\Crm\Http\Resources\Api\ServicePaymentResource;
use Taba\Crm\Models\ServicePayment;

class ServicePaymentApiController extends ApiController
{
    /**
     * List payments for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $query = ServicePayment::query()
            ->with(['user', 'post']);

        // Non-admin users can only see their own payments
        if (!$request->user()->hasRole('super_admin')) {
            $query->where('user_id', $request->user()->id);
        }

        // Filter by status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $payments = $query->orderBy('created_at', 'desc')
            ->paginate($this->getPerPage());

        return ServicePaymentResource::collection($payments)
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Get a single payment.
     */
    public function show(Request $request, ServicePayment $servicePayment): JsonResponse
    {
        // Non-admin users can only see their own payments
        if (
            !$request->user()->hasRole('super_admin') &&
            $servicePayment->user_id !== $request->user()->id
        ) {
            return $this->forbidden('You can only view your own payments');
        }

        $servicePayment->load(['user', 'post']);

        return (new ServicePaymentResource($servicePayment))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Create a new payment record (authenticated).
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'post_id'             => ['required', 'integer', 'exists:posts,id'],
            'moyasar_payment_id'  => ['required', 'string'],
            'status'              => ['required', 'string', 'in:initiated,paid,failed,authorized,captured,refunded'],
            'amount'              => ['required', 'numeric', 'min:0'],
            'currency'            => ['nullable', 'string', 'max:3'],
            'payment_method'      => ['nullable', 'string'],
            'description'         => ['nullable', 'string'],
            'fee'                 => ['nullable', 'numeric', 'min:0'],
            'metadata'            => ['nullable', 'array'],
        ]);

        $payment = ServicePayment::create(array_merge(
            $request->only([
                'post_id', 'moyasar_payment_id', 'status',
                'amount', 'currency', 'payment_method',
                'description', 'fee', 'metadata',
            ]),
            ['user_id' => $request->user()->id]
        ));

        return $this->created(new ServicePaymentResource($payment->load(['user', 'post'])));
    }
}
