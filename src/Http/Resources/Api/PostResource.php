<?php

namespace Taba\Crm\Http\Resources\Api;

use Awcodes\Curator\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Taba\Crm\Services\ResponsiveImageService;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $locale = $request->header('Accept-Language', app()->getLocale());

        $image = $this->image instanceof Media ? $this->image : null;
        $optimized = app(ResponsiveImageService::class)->forMedia($image);

        return [
            'id'          => $this->id,
            'title'       => $this->getTranslation('title', $locale, false),
            'slug'        => $this->slug,
            'content'     => $this->resolveContentBlocks($this->getTranslation('content', $locale, false)),
            'excerpt'     => $this->excerpt,
            'image_url'   => $optimized['src'] ?? $this->image?->url ?? null,
            'meta_title'  => $this->getTranslation('meta_title', $locale, false),
            'meta_description' => $this->getTranslation('meta_description', $locale, false),
            'metadata'    => $this->getTranslation('metadata', $locale, false),
            'icon'        => $this->icon,
            'show_in_home' => $this->show_in_home,
            'order'       => $this->order,
            'is_published' => $this->is_published,
            'published_at' => $this->published_at?->toIso8601String(),

            // Relationships (loaded conditionally)
            'category'    => new PostCategoryResource($this->whenLoaded('postCategory')),
            'user'        => new UserResource($this->whenLoaded('user')),
            'tags'        => TagResource::collection($this->whenLoaded('tags')),
            'image'       => new MediaResource($this->whenLoaded('image')),

            // Translations (include all when explicitly requested)
            'translations' => $this->when(
                $request->boolean('include_translations'),
                fn () => [
                    'title'            => $this->getTranslations('title'),
                    'content'          => $this->getTranslations('content'),
                    'meta_title'       => $this->getTranslations('meta_title'),
                    'meta_description' => $this->getTranslations('meta_description'),
                    'metadata'         => $this->getTranslations('metadata'),
                ]
            ),

            'created_at'  => $this->created_at?->toIso8601String(),
            'updated_at'  => $this->updated_at?->toIso8601String(),
        ];
    }

    /**
     * Resolve image URLs inside content blocks of type 'figure'.
     */
    private function resolveContentBlocks(mixed $blocks): mixed
    {
        if (!is_array($blocks)) {
            return $blocks;
        }

        return array_map(function (array $block): array {
            if (
                ($block['type'] ?? null) === 'figure' &&
                isset($block['data']['image_id']) &&
                !isset($block['data']['image_url'])
            ) {
                $media = Media::find($block['data']['image_id']);
                $optimized = app(ResponsiveImageService::class)->forMedia($media);
                $block['data']['image_url'] = $optimized['src'] ?? $media?->url ?? null;
                $block['data']['image_srcset'] = $optimized['srcset'] ?? null;
                $block['data']['image_width'] = $optimized['width'] ?? null;
                $block['data']['image_height'] = $optimized['height'] ?? null;
            }

            return $block;
        }, $blocks);
    }
}
