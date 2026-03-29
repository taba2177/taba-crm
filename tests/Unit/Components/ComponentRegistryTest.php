<?php

namespace Taba\Crm\Tests\Unit\Components;

use PHPUnit\Framework\TestCase;
use Taba\Crm\Components\Contracts\SectionComponent;
use Taba\Crm\Components\Contracts\SectionLayout;
use Taba\Crm\Components\Registry\ComponentRegistry;
use Taba\Crm\Models\PostCategory;

class ComponentRegistryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        ComponentRegistry::flush();
    }

    private function makeStubComponent(string $key = 'stub', string $labelAr = 'تجربة'): SectionComponent
    {
        return new class($key, $labelAr) implements SectionComponent {
            public function __construct(private string $k, private string $l) {}
            public function key(): string { return $this->k; }
            public function label(): array { return ['ar' => $this->l, 'en' => 'Stub']; }
            public function icon(): string { return 'heroicon-o-star'; }
            public function description(): array { return ['ar' => '', 'en' => '']; }
            public function layout(): SectionLayout { return SectionLayout::LIST; }
            public function sectionFields(): array { return []; }
            public function itemFields(): array { return []; }
            public function bladeView(): string { return 'crm::components.homepage.stub'; }
            public function toApi(PostCategory $section): array { return []; }
            public function rules(): array { return []; }
            public function maxItems(): ?int { return null; }
        };
    }

    public function test_register_and_resolve(): void
    {
        $component = $this->makeStubComponent('hero');
        ComponentRegistry::registerInstance($component);

        $resolved = ComponentRegistry::resolve('hero');
        $this->assertSame($component, $resolved);
    }

    public function test_all_returns_collection(): void
    {
        ComponentRegistry::registerInstance($this->makeStubComponent('a'));
        ComponentRegistry::registerInstance($this->makeStubComponent('b'));

        $all = ComponentRegistry::all();
        $this->assertCount(2, $all);
    }

    public function test_has_returns_true_for_registered(): void
    {
        ComponentRegistry::registerInstance($this->makeStubComponent('faq'));
        $this->assertTrue(ComponentRegistry::has('faq'));
        $this->assertFalse(ComponentRegistry::has('nonexistent'));
    }

    public function test_for_select_returns_key_label_map(): void
    {
        // forSelect() calls app()->getLocale() which needs Laravel container
        // Skip if container not available
        if (!function_exists('app') || !app()->bound('translator')) {
            $this->markTestSkipped('Requires Laravel container for app()->getLocale()');
        }

        ComponentRegistry::registerInstance($this->makeStubComponent('hero', 'الرئيسي'));
        $select = ComponentRegistry::forSelect();
        $this->assertArrayHasKey('hero', $select);
        $this->assertEquals('الرئيسي', $select['hero']);
    }

    public function test_keys_returns_registered_keys(): void
    {
        ComponentRegistry::registerInstance($this->makeStubComponent('a'));
        ComponentRegistry::registerInstance($this->makeStubComponent('b'));
        $this->assertEquals(['a', 'b'], ComponentRegistry::keys());
    }

    public function test_later_registration_overrides_earlier(): void
    {
        ComponentRegistry::registerInstance($this->makeStubComponent('hero', 'أول'));
        ComponentRegistry::registerInstance($this->makeStubComponent('hero', 'ثاني'));

        $resolved = ComponentRegistry::resolve('hero');
        $this->assertEquals('ثاني', $resolved->label()['ar']);
    }

    public function test_resolve_throws_for_unknown_key(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        ComponentRegistry::resolve('nonexistent');
    }

    public function test_flush_clears_all(): void
    {
        ComponentRegistry::registerInstance($this->makeStubComponent('x'));
        ComponentRegistry::flush();
        $this->assertCount(0, ComponentRegistry::all());
    }
}
