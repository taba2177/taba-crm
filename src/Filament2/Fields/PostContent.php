<?php

namespace Taba\Crm\Filament\Fields;

use Taba\Crm\Filament\Blocks\Image;
use Taba\Crm\Filament\Blocks\Paragraph;
use Taba\Crm\Filament\Blocks\Title;
use Filament\Forms\Components\Builder;

class PostContent
{
    public static function make(
        string $name,
        string $context = 'form',
    ): Builder {
        return Builder::make($name)
            ->blocks([
                Title::make(context: $context),
                Paragraph::make(context: $context),
                Image::make(context: $context),
            ])
            ->collapsible();
    }
}
