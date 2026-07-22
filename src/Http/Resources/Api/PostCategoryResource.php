<?php

namespace Taba\Crm\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Taba\Crm\Services\ResponsiveImageService;

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
            'image_url'    => $this->optimizedImageUrl(),
            'image_srcset' => $this->optimizedImageSrcset(),
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

    /**
     * Category images are stored as raw paths (not Curator Media). When the
     * path is a local, resizable file we serve an optimized WebP through Glide;
     * remote URLs and non-resizable files pass through untouched.
     */
    private function optimizedImageUrl(): ?string
    {
        if (! $this->image) {
            return null;
        }

        $image = (string) $this->image;

        if (str_starts_with($image, 'http')) {
            return $image;
        }

        $ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));
        if (function_exists('is_media_resizable') && is_media_resizable($ext)) {
            return app(ResponsiveImageService::class)->url(ltrim($image, '/'), ResponsiveImageService::DEFAULT_WIDTH);
        }

        return Storage::url($image);
    }

    private function optimizedImageSrcset(): ?string
    {
        if (! $this->image) {
            return null;
        }

        $image = (string) $this->image;
        if (str_starts_with($image, 'http')) {
            return null;
        }

        $ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));
        if (function_exists('is_media_resizable') && is_media_resizable($ext)) {
            return app(ResponsiveImageService::class)->srcset(ltrim($image, '/'));
        }

        return null;
    }
}
