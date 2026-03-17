<?php

namespace Taba\Crm\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Taba\Crm\Http\Resources\Api\ReviewResource;
use Taba\Crm\Models\Review;

class ReviewApiController extends ApiController
{
    /**
     * List all reviews.
     */
    public function index(Request $request): JsonResponse
    {
        $cacheTtl = config('crm.api.cache_ttl', 300);

        $data = Cache::remember('api_reviews_' . md5($request->fullUrl()), $cacheTtl, function () use ($request) {
            $query = Review::query();

            // Filter by minimum rating
            if ($request->has('min_rating')) {
                $query->where('rating', '>=', (int) $request->input('min_rating'));
            }

            return $query->orderBy('created_at', 'desc')
                ->paginate($this->getPerPage());
        });

        return ReviewResource::collection($data)
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Get a single review.
     */
    public function show(Review $review): JsonResponse
    {
        return (new ReviewResource($review))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Create a review (authenticated).
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:5000'],
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'img'     => ['nullable', 'string'],
            'images'  => ['nullable', 'array'],
        ]);

        $review = Review::create($request->only(['name', 'content', 'rating', 'img', 'images']));

        return $this->created(new ReviewResource($review));
    }

    /**
     * Update a review (authenticated).
     */
    public function update(Request $request, Review $review): JsonResponse
    {
        $request->validate([
            'name'    => ['sometimes', 'string', 'max:255'],
            'content' => ['sometimes', 'string', 'max:5000'],
            'rating'  => ['sometimes', 'integer', 'min:1', 'max:5'],
            'img'     => ['nullable', 'string'],
            'images'  => ['nullable', 'array'],
        ]);

        $review->update($request->only(['name', 'content', 'rating', 'img', 'images']));

        return $this->success(new ReviewResource($review));
    }

    /**
     * Delete a review (authenticated).
     */
    public function destroy(Review $review): JsonResponse
    {
        $review->delete();

        return $this->success(null, 'Review deleted successfully');
    }
}
