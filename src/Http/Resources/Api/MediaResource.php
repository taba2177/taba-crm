<?php

namespace Taba\Crm\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'path'      => $this->path,
            'url'       => $this->url,
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
