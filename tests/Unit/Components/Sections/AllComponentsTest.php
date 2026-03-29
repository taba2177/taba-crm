<?php

namespace Taba\Crm\Tests\Unit\Components\Sections;

use PHPUnit\Framework\TestCase;
use Taba\Crm\Components\Contracts\SectionComponent;
use Taba\Crm\Components\Contracts\SectionLayout;

class AllComponentsTest extends TestCase
{
    /**
     * Get all component classes by scanning the Sections directory.
     */
    private function getAllComponentClasses(): array
    {
        $dir = __DIR__ . '/../../../../src/Components/Sections';
        $classes = [];
        foreach (glob($dir . '/*.php') as $file) {
            $className = 'Taba\\Crm\\Components\\Sections\\' . basename($file, '.php');
            if (class_exists($className)) {
                $classes[] = $className;
            }
        }
        return $classes;
    }

    public function test_all_components_implement_section_component(): void
    {
        $classes = $this->getAllComponentClasses();
        $this->assertGreaterThanOrEqual(25, count($classes), 'Expected at least 25 component classes');
        
        foreach ($classes as $className) {
            $component = new $className();
            $this->assertInstanceOf(SectionComponent::class, $component, "{$className} must implement SectionComponent");
        }
    }

    public function test_all_components_have_required_properties(): void
    {
        $classes = $this->getAllComponentClasses();
        
        foreach ($classes as $className) {
            $component = new $className();
            
            $this->assertNotEmpty($component->key(), "{$className}::key() must not be empty");
            $this->assertNotEmpty($component->icon(), "{$className}::icon() must not be empty");
            $this->assertNotEmpty($component->bladeView(), "{$className}::bladeView() must not be empty");
            
            $label = $component->label();
            $this->assertArrayHasKey('ar', $label, "{$className}::label() must have 'ar' key");
            $this->assertArrayHasKey('en', $label, "{$className}::label() must have 'en' key");
        }
    }

    public function test_single_layout_components_have_empty_item_fields(): void
    {
        $classes = $this->getAllComponentClasses();
        
        foreach ($classes as $className) {
            $component = new $className();
            
            if ($component->layout() === SectionLayout::SINGLE) {
                $this->assertEmpty(
                    $component->itemFields(), 
                    "{$className} has SINGLE layout but non-empty itemFields()"
                );
            }
        }
    }

    public function test_all_keys_are_unique(): void
    {
        $classes = $this->getAllComponentClasses();
        $keys = [];
        
        foreach ($classes as $className) {
            $component = new $className();
            $key = $component->key();
            $this->assertArrayNotHasKey($key, $keys, "Duplicate key '{$key}' found in {$className}");
            $keys[$key] = $className;
        }
    }

    public function test_all_icons_start_with_heroicon(): void
    {
        $classes = $this->getAllComponentClasses();
        
        foreach ($classes as $className) {
            $component = new $className();
            $this->assertStringStartsWith('heroicon-', $component->icon(), "{$className}::icon() must start with 'heroicon-'");
        }
    }
}
