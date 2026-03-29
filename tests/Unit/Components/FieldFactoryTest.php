<?php

namespace Taba\Crm\Tests\Unit\Components;

use PHPUnit\Framework\TestCase;
use Taba\Crm\Components\Fields\FieldFactory;

class FieldFactoryTest extends TestCase
{
    protected function tearDown(): void
    {
        FieldFactory::reset();
        parent::tearDown();
    }

    public function test_make_returns_filament_text_input(): void
    {
        $field = FieldFactory::make('text', 'title', ['translatable' => true]);
        $this->assertInstanceOf(\Filament\Forms\Components\TextInput::class, $field);
    }

    public function test_make_returns_textarea(): void
    {
        $field = FieldFactory::make('textarea', 'description');
        $this->assertInstanceOf(\Filament\Forms\Components\Textarea::class, $field);
    }

    public function test_make_returns_richtext(): void
    {
        if (! app()->bound('config')) {
            $this->markTestSkipped('TiptapEditor requires Laravel container (config service).');
        }
        $field = FieldFactory::make('richtext', 'content');
        $this->assertInstanceOf(\FilamentTiptapEditor\TiptapEditor::class, $field);
    }

    public function test_make_returns_toggle(): void
    {
        $field = FieldFactory::make('toggle', 'is_active');
        $this->assertInstanceOf(\Filament\Forms\Components\Toggle::class, $field);
    }

    public function test_make_returns_number(): void
    {
        $field = FieldFactory::make('number', 'price');
        $this->assertInstanceOf(\Filament\Forms\Components\TextInput::class, $field);
    }

    public function test_make_returns_select(): void
    {
        $field = FieldFactory::make('select', 'type', ['options' => ['a' => 'A', 'b' => 'B']]);
        $this->assertInstanceOf(\Filament\Forms\Components\Select::class, $field);
    }

    public function test_make_returns_url(): void
    {
        $field = FieldFactory::make('url', 'link');
        $this->assertInstanceOf(\Filament\Forms\Components\TextInput::class, $field);
    }

    public function test_make_returns_color(): void
    {
        $field = FieldFactory::make('color', 'bg_color');
        $this->assertInstanceOf(\Filament\Forms\Components\ColorPicker::class, $field);
    }

    public function test_extend_registers_custom_field_type(): void
    {
        FieldFactory::extend('custom', fn (string $name, array $opts) =>
            \Filament\Forms\Components\TextInput::make($name)->label('Custom')
        );
        $field = FieldFactory::make('custom', 'my_field');
        $this->assertInstanceOf(\Filament\Forms\Components\TextInput::class, $field);
    }

    public function test_make_throws_for_unknown_type(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        FieldFactory::make('nonexistent', 'field');
    }
}
