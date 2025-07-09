<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;

class SectionSelector extends Field
{
    protected string $view = 'filament.forms.components.section-selector';

    public array $options = [];

    public function options(array $options): static
    {
        $processedOptions = [];
        foreach ($options as $value => $label) {
            $processedOptions[$value] = (string) (is_array($label) ? __($label) : $label);
        }
        $this->options = $processedOptions;

        return $this;
    }
}
