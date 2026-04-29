<?php

namespace Taba\Crm\Tests\Feature;

use Taba\Crm\Tests\TestCase;

class WidgetClassLoadingTest extends TestCase
{
    /**
     * Force-load every Filament Client widget class so PHP runs the
     * static/instance compatibility check against each parent. Catches
     * issues like declaring a property as static when the parent has it
     * as an instance property — which only surfaces at autoload time
     * (e.g. during `package:discover` in a host app), never during
     * normal test runs because PSR-4 autoload is lazy.
     */
    public function test_all_filament_client_widgets_can_be_autoloaded(): void
    {
        $dir = __DIR__ . '/../../src/Filament/Client/Widgets';
        $files = glob($dir . '/*.php');
        $this->assertNotEmpty($files, 'No widget files found.');

        foreach ($files as $file) {
            $class = 'Taba\\Crm\\Filament\\Client\\Widgets\\' . basename($file, '.php');
            $this->assertTrue(
                class_exists($class),
                "Widget class {$class} failed to load — likely a static/instance property conflict with the parent class."
            );
        }
    }
}
