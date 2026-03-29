<?php

namespace Taba\Crm\Tests\Unit\Components;

use PHPUnit\Framework\TestCase;
use Taba\Crm\Components\Contracts\SectionComponent;
use Taba\Crm\Components\Contracts\SectionLayout;

class SectionComponentInterfaceTest extends TestCase
{
    public function test_section_layout_enum_has_single_and_list(): void
    {
        $this->assertEquals('SINGLE', SectionLayout::SINGLE->name);
        $this->assertEquals('LIST', SectionLayout::LIST->name);
        $this->assertCount(2, SectionLayout::cases());
    }

    public function test_interface_can_be_implemented(): void
    {
        $component = new class implements SectionComponent {
            public function key(): string { return 'test'; }
            public function label(): array { return ['ar' => 'تجربة', 'en' => 'Test']; }
            public function icon(): string { return 'heroicon-o-star'; }
            public function description(): array { return ['ar' => 'وصف', 'en' => 'Desc']; }
            public function layout(): SectionLayout { return SectionLayout::SINGLE; }
            public function sectionFields(): array { return []; }
            public function itemFields(): array { return []; }
            public function bladeView(): string { return 'crm::components.homepage.test'; }
            public function toApi(\Taba\Crm\Models\PostCategory $section): array { return []; }
            public function rules(): array { return []; }
            public function maxItems(): ?int { return null; }
        };

        $this->assertInstanceOf(SectionComponent::class, $component);
        $this->assertEquals('test', $component->key());
        $this->assertEquals(SectionLayout::SINGLE, $component->layout());
    }
}
