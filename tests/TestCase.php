<?php

namespace Taba\Crm\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Taba\Crm\CrmServiceProvider;
use Spatie\Permission\PermissionServiceProvider;

abstract class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        // Register package factory autoloader before anything runs
        spl_autoload_register(static function (string $class): void {
            $prefix = 'Taba\\Crm\\Database\\Factories\\';
            if (str_starts_with($class, $prefix)) {
                $file = __DIR__ . '/../database/factories/' . substr($class, strlen($prefix)) . '.php';
                if (file_exists($file)) {
                    require_once $file;
                }
            }
        });

        // Register package seeders autoloader for test runtime
        spl_autoload_register(static function (string $class): void {
            $prefix = 'Taba\\Crm\\Database\\Seeders\\';
            if (str_starts_with($class, $prefix)) {
                $file = __DIR__ . '/../database/seeders/' . substr($class, strlen($prefix)) . '.php';
                if (file_exists($file)) {
                    require_once $file;
                }
            }
        });

        parent::setUp();

        $this->setUpDatabase();
    }

    protected function getPackageProviders($app)
    {
        return [
            CrmServiceProvider::class,
            LivewireServiceProvider::class,
            PermissionServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        // Setup CRM config
        $app['config']->set('crm.gemini_api_key', 'test_api_key');
        $app['config']->set('crm.locale', 'en');
        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));

        // Define the sanctum guard so auth:sanctum middleware resolves correctly in tests
        $app['config']->set('auth.guards.sanctum', [
            'driver'   => 'session',
            'provider' => 'users',
        ]);
    }

    protected function setUpDatabase()
    {
        // Test-only fixtures first (e.g. users table — owned by the host app, not the package)
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
