<?php

namespace Taba\Crm\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServicePaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'moyasar_payment_id' => $this->moyasar_payment_id,
            'status'             => $this->status,
            'amount'             => $this->amount,
            'currency'           => $this->currency,
            'payment_method'     => $this->payment_method,
            'description'        => $this->description,
            'fee'                => $this->fee,
            'metadata'           => $this->metadata,
            'refunded_at'        => $this->refunded_at?->toIso8601String(),
            'refunded_amount'    => $this->refunded_amount,

            // Relationships
            'user' => new UserResource($this->whenLoaded('user')),
            'post' => new PostResource($this->whenLoaded('post')),

            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
