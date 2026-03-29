<?php

namespace Taba\Crm\Tests\Feature;

use Orchestra\Testbench\TestCase;
use Taba\Crm\Components\Contracts\SectionComponent;
use Taba\Crm\Components\Contracts\SectionLayout;
use Taba\Crm\Components\Registry\ComponentRegistry;
use Taba\Crm\CrmServiceProvider;
use Taba\Crm\Models\PostCategory;

class ComponentFlowTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [CrmServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('crm.locale', 'ar');
        $app['config']->set('crm.api.enabled', true);
        $app['config']->set('app.key', 'base64:' . base64_encode(random_bytes(32)));
    }

    protected function defineDatabaseMigrations(): void
    {
        $schema = $this->app['db']->connection()->getSchemaBuilder();

        // Create only the tables needed for these tests
        $schema->create('post_categories', function ($table) {
            $table->id();
            $table->timestamps();
            $table->string('slug')->unique();
            $table->json('name')->nullable();
            $table->boolean('register_in_header')->default(true);
            $table->boolean('HEAVY_SECTION')->default(true);
            $table->string('section_component')->nullable();
            $table->integer('order')->default(0);
            $table->json('description')->nullable();
            $table->json('subtitle')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('image')->nullable();
        });

        $schema->create('crm_settings', function ($table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('value')->nullable();
            $table->string('type')->default('text');
            $table->string('group')->default('general');
            $table->json('label')->nullable();
            $table->json('description')->nullable();
            $table->boolean('is_translatable')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        $schema->create('menus', function ($table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('group')->nullable();
            $table->text('items')->nullable();
        });
    }

    // === Component Registration Tests ===

    public function test_custom_component_registers_and_resolves(): void
    {
        $fake = new class implements SectionComponent {
            public function key(): string { return 'test-flow'; }
            public function label(): array { return ['ar' => 'تجريبي', 'en' => 'Test Flow']; }
            public function icon(): string { return 'heroicon-o-beaker'; }
            public function description(): array { return ['ar' => 'وصف', 'en' => 'Description']; }
            public function layout(): SectionLayout { return SectionLayout::LIST; }
            public function sectionFields(): array { return []; }
            public function itemFields(): array { return []; }
            public function bladeView(): string { return 'crm::components.homepage.default'; }
            public function toApi(PostCategory $section): array {
                return [
                    'id' => $section->id,
                    'component' => $this->key(),
                    'order' => $section->order,
                    'is_active' => (bool) $section->is_active,
                    'title' => $section->getTranslations('name'),
                    'items' => [],
                ];
            }
            public function rules(): array { return []; }
            public function maxItems(): ?int { return 10; }
        };

        ComponentRegistry::registerInstance($fake);

        $this->assertTrue(ComponentRegistry::has('test-flow'));
        $resolved = ComponentRegistry::resolve('test-flow');
        $this->assertSame('test-flow', $resolved->key());
        $this->assertSame(SectionLayout::LIST, $resolved->layout());
        $this->assertSame(10, $resolved->maxItems());
    }

    public function test_built_in_hero_component_is_available(): void
    {
        $this->assertTrue(ComponentRegistry::has('hero'));
        $component = ComponentRegistry::resolve('hero');
        $this->assertSame('hero', $component->key());
        $this->assertArrayHasKey('ar', $component->label());
    }

    // === API v2 Tests ===

    public function test_components_endpoint_lists_registered_types(): void
    {
        $response = $this->getJson('/api/v2/components');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['key', 'label', 'icon', 'layout'],
                ],
            ]);

        $keys = collect($response->json('data'))->pluck('key');
        $this->assertTrue($keys->contains('hero'));
        $this->assertTrue($keys->contains('faq'));
    }

    public function test_sections_endpoint_returns_empty_when_no_sections(): void
    {
        $response = $this->getJson('/api/v2/sections');
        $response->assertOk()
            ->assertJsonPath('data', []);
    }

    public function test_sections_endpoint_returns_component_typed_data(): void
    {
        $category = PostCategory::create([
            'name' => ['ar' => 'خدماتنا', 'en' => 'Our Services'],
            'slug' => 'services-flow-test',
            'section_component' => 'services-grid',
            'is_active' => true,
            'order' => 1,
        ]);

        $response = $this->getJson('/api/v2/sections');

        $response->assertOk();
        $data = $response->json('data');
        $this->assertNotEmpty($data);
        $this->assertEquals('services-grid', $data[0]['component']);
        $this->assertEquals($category->id, $data[0]['id']);
    }

    public function test_sections_endpoint_excludes_inactive(): void
    {
        PostCategory::create([
            'name' => ['ar' => 'مخفي'],
            'slug' => 'hidden-flow-test',
            'section_component' => 'hero',
            'is_active' => false,
            'order' => 1,
        ]);

        $response = $this->getJson('/api/v2/sections');
        $response->assertOk()
            ->assertJsonCount(0, 'data');
    }

    public function test_single_section_endpoint(): void
    {
        $category = PostCategory::create([
            'name' => ['ar' => 'اختبار'],
            'slug' => 'single-test',
            'section_component' => 'hero',
            'is_active' => true,
            'order' => 1,
        ]);

        $response = $this->getJson('/api/v2/sections/' . $category->id);

        $response->assertOk()
            ->assertJsonPath('data.component', 'hero')
            ->assertJsonPath('data.id', $category->id);
    }

    public function test_settings_endpoint(): void
    {
        $response = $this->getJson('/api/v2/settings');
        $response->assertOk()
            ->assertJsonStructure(['data']);
    }

    public function test_menus_endpoint(): void
    {
        $response = $this->getJson('/api/v2/menus');
        $response->assertOk()
            ->assertJsonStructure(['data']);
    }
}
