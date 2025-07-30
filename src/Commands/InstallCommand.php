<?php

// packages/taba/crm/src/Commands/InstallCommand.php

namespace Taba\Crm\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

class InstallCommand extends Command
{
    protected $signature = 'crm:install';
    protected $description = 'Install all assets and configurations for the Taba CRM package.';

    public function handle()
    {
        $this->info('Installing Taba CRM package...');

        $this->comment('1. Running dependency installers...');
        $this->callSilent('filament:install', ['--panels' => true, '--no-interaction' => true]);
        $this->callSilent('curator:install', ['--no-interaction' => true]);

        // Set the current panel to avoid issues with commands that need panel context
        if (class_exists(\App\Providers\Filament\AdminPanelProvider::class)) {
            \Filament\Facades\Filament::setCurrentPanel(\Filament\Facades\Filament::getPanel('admin'));
        }

        $this->callSilent('vendor:publish', ['--tag' => 'filament-peek-assets', '--force' => true]);

        $this->comment('2. Publishing package assets (config, database)...');
        $this->callSilent('vendor:publish', ['--tag' => 'crm-config', '--force' => true]);
        $this->callSilent('vendor:publish', ['--tag' => 'crm-database', '--force' => true]);

        $this->comment('3. Updating frontend dependencies (package.json)...');
        $this->updateNodeDependencies();

        $this->comment('4. Configuring Tailwind CSS (tailwind.config.js)...');
        $this->updateTailwindConfig();

        $this->comment('5. Configuring Vite (vite.config.js)...');
        $this->updateViteConfig();

        $this->comment('6. Running database migrations...');
        $this->callSilent('migrate');

        $this->comment('7. Installing NPM packages...');
        Process::run('npm install', function (string $type, string $buffer) {
            $this->output->write($buffer);
        });

        $this->comment('8. Building frontend assets...');
        Process::run('npm run build', function (string $type, string $buffer) {
            $this->output->write($buffer);
        });

        $this->info('✅ Taba CRM installed successfully!');
        $this->warn('Final step: Please add `->plugin(\Taba\Crm\CrmPlugin::make())` to your AdminPanelProvider to activate the plugin.');
    }

    protected function updateNodeDependencies(): void
    {
        if (!File::exists(base_path('package.json'))) {
            $this->warn('package.json not found. Skipping dependency update.');
            return;
        }

        // Use a minimal, correct list of essential dependencies
        $packages = [
            'tailwindcss' => '^3.4.0',
            'postcss' => '^8.4.38',
            'autoprefixer' => '^10.4.19',
            'vite' => '^7.0.0',
            'laravel-vite-plugin' => '^2.0.0',
            '@tailwindcss/forms' => '^0.5.7',      // Keep Filament plugins
            '@tailwindcss/typography' => '^0.5.10', // Keep Filament plugins
        ];

        $packageJson = json_decode(File::get(base_path('package.json')), true);

        // Add or update dependencies in devDependencies
        foreach ($packages as $package => $version) {
            $packageJson['devDependencies'][$package] = $version;
        }

        File::put(base_path('package.json'), json_encode($packageJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    protected function updateTailwindConfig(): void
    {
        $configPath = base_path('tailwind.config.js');
        $presetPath = './packages/taba/crm/tailwind-preset.js'; // Correct path to preset

        if (!File::exists($configPath)) {
            // If tailwind.config.js doesn't exist, create a new one using the preset.
            $content = "module.exports = {\n    presets: [require('{$presetPath}')],\n    content: [\n        './app/Filament/**/*.php',\n        './resources/views/filament/**/*.blade.php',\n        './vendor/filament/**/*.blade.php',\n        './packages/taba/crm/resources/views/**/*.blade.php', // Add crm views\n    ],\n};\n";
            File::put($configPath, $content);
            $this->info('Created tailwind.config.js with CRM preset.');
            return;
        }

        // If it exists, add our preset non-destructively
        $content = File::get($configPath);
        $presetRequire = "require('{$presetPath}')";

        if (!str_contains($content, $presetRequire)) {
            $newContent = preg_replace(
                '/(presets\s*:\s*\[)/',
                "$1\n        {$presetRequire},",
                $content,
                1,
                $count
            );

            if ($count === 0) { // If 'presets' key doesn't exist, add it
                 $newContent = preg_replace(
                    '/(module\\.exports\\s*=\\s*{)/',
                    "$1\n    presets: [{$presetRequire}],",
                    $content
                );
            }
            File::put($configPath, $newContent);
            $this->info('Updated tailwind.config.js with CRM preset.');
        }
    }

// packages/taba/crm/src/Commands/InstallCommand.php

    protected function updateViteConfig(): void
    {
        $configPath = base_path('vite.config.js');
        $viteThemePath = "'vendor/taba/crm/src/resources/css/admin.css'";

        // If vite.config.js does not exist, create it from a standard Laravel stub.
        if (! File::exists($configPath)) {
            $this->info('vite.config.js not found. Creating a new one for you...');
            $stub = <<<'EOT'
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
EOT;
            File::put($configPath, $stub);
        }

        // Now that we know the file exists, inject our theme path if it's missing.
        $content = File::get($configPath);

        if (! str_contains($content, $viteThemePath)) {
            $newContent = str_replace(
                "'resources/js/app.js',",
                "'resources/js/app.js',\n                {$viteThemePath},",
                $content
            );
            File::put($configPath, $newContent);
            $this->info('CRM theme path added to vite.config.js.');
        }
    }
}
