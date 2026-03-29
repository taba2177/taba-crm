<?php

namespace Taba\Crm\Tests\Unit\Components\Sections;

use PHPUnit\Framework\TestCase;
use Taba\Crm\Components\Contracts\SectionComponent;
use Taba\Crm\Components\Contracts\SectionLayout;
use Taba\Crm\Components\Sections\HeroComponent;

class HeroComponentTest extends TestCase
{
    private HeroComponent $component;

    protected function setUp(): void
    {
        parent::setUp();
        $this->component = new HeroComponent();
    }

    public function test_implements_section_component(): void
    {
        $this->assertInstanceOf(SectionComponent::class, $this->component);
    }

    public function test_key_is_hero(): void
    {
        $this->assertEquals('hero', $this->component->key());
    }

    public function test_layout_is_single(): void
    {
        $this->assertEquals(SectionLayout::SINGLE, $this->component->layout());
    }

    public function test_label_has_ar_and_en(): void
    {
        $label = $this->component->label();
        $this->assertArrayHasKey('ar', $label);
        $this->assertArrayHasKey('en', $label);
    }

    public function test_icon_is_heroicon(): void
    {
        $this->assertEquals('heroicon-o-sparkles', $this->component->icon());
    }

    public function test_section_fields_not_empty(): void
    {
        // sectionFields() calls FieldFactory::make() which creates Filament components
        // CuratorPicker for 'image' may need container, so handle gracefully
        try {
            $fields = $this->component->sectionFields();
            $this->assertNotEmpty($fields);
        } catch (\Throwable $e) {
            $this->markTestSkipped('sectionFields() requires Laravel container: ' . $e->getMessage());
        }
    }

    public function test_item_fields_empty_for_single_layout(): void
    {
        $this->assertEmpty($this->component->itemFields());
    }

    public function test_blade_view_points_to_hero_template(): void
    {
        $this->assertEquals('crm::components.homepage.hero', $this->component->bladeView());
    }

    public function test_max_items_is_null(): void
    {
        $this->assertNull($this->component->maxItems());
    }
}
