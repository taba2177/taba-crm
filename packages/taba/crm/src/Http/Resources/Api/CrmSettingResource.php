<?php

namespace Taba\Crm\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CrmSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $locale = $request->header('Accept-Language', app()->getLocale());

        return [
            'id'     => $this->id,
            'key'    => $this->key,
            'value'  => $this->is_translatable && is_array($this->value)
                ? ($this->value[$locale] ?? $this->value['en'] ?? $this->value)
                : $this->value,
            'type'   => $this->type,
            'group'  => $this->group,
            'label'  => $this->getTranslation('label', $locale, false),

            'translations' => $this->when(
                $request->boolean('include_translations'),
                fn () => [
                    'label'       => $this->getTranslations('label'),
                    'description' => $this->getTranslations('description'),
                ]
            ),
        ];
    }
}
