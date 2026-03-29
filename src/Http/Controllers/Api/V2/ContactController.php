<?php

namespace Taba\Crm\Http\Controllers\Api\V2;

use Illuminate\Http\JsonResponse;
use Taba\Crm\Http\Controllers\Api\ApiController;
use Taba\Crm\Http\Requests\Api\StoreContactEntryRequest;
use Taba\Crm\Models\ContactEntry;

class ContactController extends ApiController
{
    /**
     * Submit a contact message.
     * POST /api/v2/contact
     */
    public function store(StoreContactEntryRequest $request): JsonResponse
    {
        $entry = ContactEntry::create($request->validated());

        return $this->created([
            'id' => $entry->id,
        ], 'Contact message sent successfully');
    }
}
