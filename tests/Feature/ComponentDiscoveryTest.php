<?php

namespace Taba\Crm\Tests\Feature;

use Orchestra\Testbench\TestCase;
use Taba\Crm\Components\Registry\ComponentRegistry;
use Taba\Crm\CrmServiceProvider;

class ComponentDiscoveryTest extends TestCase
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
        $app['config']->set('app.key', 'base64:' . base64_encode(random_bytes(32)));
    }

    public function test_built_in_components_are_auto_discovered(): void
    {
        $this->assertTrue(ComponentRegistry::has('hero'));
        $this->assertTrue(ComponentRegistry::has('faq'));
        $this->assertTrue(ComponentRegistry::has('services-grid'));
    }

    public function test_all_returns_at_least_25_components(): void
    {
        $this->assertGreaterThanOrEqual(25, ComponentRegistry::all()->count());
    }

    public function test_config_extra_components_are_registered(): void
    {
        $this->assertIsArray(config('crm.extra_components', []));
    }
}
