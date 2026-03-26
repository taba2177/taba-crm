<?php

namespace Taba\Crm\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Taba\Crm\Http\Requests\Api\StoreContactEntryRequest;
use Taba\Crm\Http\Resources\Api\ContactEntryResource;
use Taba\Crm\Models\ContactEntry;

class ContactEntryApiController extends ApiController
{
    /**
     * List all contact entries (authenticated, admin).
     */
    public function index(Request $request): JsonResponse
    {
        $entries = ContactEntry::orderBy('created_at', 'desc')
            ->paginate($this->getPerPage());

        return ContactEntryResource::collection($entries)
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Get a single contact entry (authenticated, admin).
     */
    public function show(ContactEntry $contactEntry): JsonResponse
    {
        return (new ContactEntryResource($contactEntry))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Submit a new contact entry (public endpoint).
     */
    public function store(StoreContactEntryRequest $request): JsonResponse
    {
        $entry = ContactEntry::create($request->validated());

        return $this->created(new ContactEntryResource($entry), 'Contact message sent successfully');
    }

    /**
     * Delete a contact entry (authenticated, admin).
     */
    public function destroy(ContactEntry $contactEntry): JsonResponse
    {
        $contactEntry->delete();

        return $this->success(null, 'Contact entry deleted successfully');
    }
}
