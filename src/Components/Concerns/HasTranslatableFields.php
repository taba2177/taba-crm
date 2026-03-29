<?php

namespace Taba\Crm\Components\Concerns;

trait HasTranslatableFields
{
    protected function translatableLabel(array $labels): string
    {
        $locale = app()->getLocale();
        return $labels[$locale] ?? $labels['ar'] ?? reset($labels);
    }
}
