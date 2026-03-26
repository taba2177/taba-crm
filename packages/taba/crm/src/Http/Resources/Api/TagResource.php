<?php

namespace Taba\Crm\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $locale = $request->header('Accept-Language', app()->getLocale());

        return [
            'id'   => $this->id,
            'name' => $this->getTranslation('name', $locale, false),
            'slug' => $this->slug,

            'posts_count' => $this->whenCounted('posts'),

            'translations' => $this->when(
                $request->boolean('include_translations'),
                fn () => [
                    'name' => $this->getTranslations('name'),
                ]
            ),

            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
