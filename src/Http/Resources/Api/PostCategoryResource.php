<?php

namespace Taba\Crm\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PostCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $locale = $request->header('Accept-Language', app()->getLocale());

        return [
            'id'           => $this->id,
            'name'         => $this->getTranslation('name', $locale, false),
            'slug'         => $this->slug,
            'description'  => $this->getTranslation('description', $locale, false),
            'subtitle'     => $this->getTranslation('subtitle', $locale, false),
            'image'        => $this->image,
            'image_url'    => $this->image
                ? (str_starts_with((string) $this->image, 'http') ? $this->image : Storage::url($this->image))
                : null,
            'order'        => $this->order,
            'is_active'    => $this->is_active,
            'parent_id'    => $this->parent_id,
            'section_component' => $this->section_component,
            'register_in_header' => $this->register_in_header,

            // Relationships
            'parent'   => new PostCategoryResource($this->whenLoaded('parent')),
            'children' => PostCategoryResource::collection($this->whenLoaded('children')),
            'posts_count' => $this->whenCounted('posts'),

            // Translations
            'translations' => $this->when(
                $request->boolean('include_translations'),
                fn () => [
                    'name'        => $this->getTranslations('name'),
                    'description' => $this->getTranslations('description'),
                    'subtitle'    => $this->getTranslations('subtitle'),
                ]
            ),

            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
