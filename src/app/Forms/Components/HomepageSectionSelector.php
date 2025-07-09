<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class HomepageSectionSelector extends Field
{
    protected string $view = 'forms.components.homepage-section-selector';

    public static function getHomepageComponentOptions(): array
    {
        $componentPath = resource_path('views/components/homepage');
        $files = File::files($componentPath);
        $options = [];

        foreach ($files as $file) {
            $name = Str::before($file->getFilename(), '.blade.php');
            $options[$name] = Str::title(str_replace('-', ' ', $name));
        }

        return $options;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->viewData(['options' => static::getHomepageComponentOptions()]);
    }
}
