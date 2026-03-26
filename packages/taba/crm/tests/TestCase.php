<?php

namespace Taba\Crm\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as Orchestra;
use Taba\Crm\CrmServiceProvider;
use Spatie\Permission\PermissionServiceProvider;

abstract class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    protected function getPackageProviders($app)
    {
        return [
            CrmServiceProvider::class,
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
    }

    protected function setUpDatabase()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        
        // Run permission migrations
        include_once __DIR__ . '/../../../vendor/spatie/laravel-permission/database/migrations/create_permission_tables.php.stub';
        (new \CreatePermissionTables())->up();
    }
}
