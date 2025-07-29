<?php

namespace Taba\Crm\Filament\Fields;

use FilamentTiptapEditor\TiptapEditor;

class PageContent
{
    public static function make(
        string $name,
        string $context = 'form',
    ): TiptapEditor {
        return TiptapEditor::make($name)->columnSpanFull();
    }
}