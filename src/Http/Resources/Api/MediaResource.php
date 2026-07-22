<?php

namespace Taba\Crm\Http\Resources\Api;

use Awcodes\Curator\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Taba\Crm\Services\ResponsiveImageService;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        // Guard: when the relation is unloaded the underlying resource can be a
        // MissingValue rather than a Media model, which must not reach the
        // strictly-typed service.
        $media = $this->resource instanceof Media ? $this->resource : null;
        $optimized = app(ResponsiveImageService::class)->forMedia($media);

        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'path'      => $this->path,
            // `url` is the optimized (WebP, resized) URL so existing frontend
            // bindings (`image.url`, `img.url`) automatically get the smaller
            // file. `original` preserves the full-resolution source for cases
            // that need it (e.g. lightbox / downloads).
            'url'       => $optimized['src'] ?? $this->url,
            'srcset'    => $optimized['srcset'] ?? null,
            'original'  => $this->url,
            'type'      => $this->type,
            'alt'       => $this->alt,
            'title'     => $this->title,
            'disk'      => $this->disk,
            'width'     => $this->width,
            'height'    => $this->height,
            'size'      => $this->size,
            'ext'       => $this->ext,

            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
