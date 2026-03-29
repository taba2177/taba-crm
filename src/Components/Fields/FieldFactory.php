<?php

namespace Taba\Crm\Components\Fields;

use Filament\Forms\Components;
use FilamentTiptapEditor\TiptapEditor;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use InvalidArgumentException;

class FieldFactory
{
    protected static array $extensions = [];

    public static function extend(string $type, callable $factory): void
    {
        static::$extensions[$type] = $factory;
    }

    public static function make(string $type, string $name, array $options = []): \Filament\Forms\Components\Component
    {
        if (isset(static::$extensions[$type])) {
            return call_user_func(static::$extensions[$type], $name, $options);
        }

        return match ($type) {
            'text' => Components\TextInput::make($name),
            'textarea' => Components\Textarea::make($name),
            'richtext' => TiptapEditor::make($name),
            'image' => CuratorPicker::make($name),
            'url' => Components\TextInput::make($name)->url(),
            'icon' => Components\TextInput::make($name),
            'number' => Components\TextInput::make($name)->numeric(),
            'toggle' => Components\Toggle::make($name),
            'select' => Components\Select::make($name)
                ->options($options['options'] ?? []),
            'color' => Components\ColorPicker::make($name),
            'date' => Components\DatePicker::make($name),
            'location' => Components\TextInput::make($name),
            default => throw new InvalidArgumentException("Unknown field type: {$type}"),
        };
    }

    public static function reset(): void
    {
        static::$extensions = [];
    }
}
