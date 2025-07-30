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
        $this->callSilent('breezy:install', ['--no-interaction' => true]);
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

        $packages = [
            '@tailwindcss/forms' => '^0.5.7',
            '@tailwindcss/typography' => '^0.5.10',
            'autoprefixer' => '^10.4.16',
            'postcss' => '^8.4.32',
            'tailwindcss' => '^3.3.6',
            'vite' => '^5.0.0',
        ];

        $packageJson = json_decode(File::get(base_path('package.json')), true);

        // Add or update dependencies
        foreach ($packages as $package => $version) {
            $packageJson['devDependencies'][$package] = $version;
        }

        File::put(base_path('package.json'), json_encode($packageJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    protected function updateTailwindConfig(): void
    {
        $configPath = base_path('tailwind.config.js');
        if (!File::exists($configPath)) {
            // If it doesn't exist, create a new one with our preset
            File::copy(__DIR__.'/../../tailwind.config.js', $configPath);
            $this->info('Created tailwind.config.js with CRM preset.');
            return;
        }

        // If it exists, add our preset non-destructively
        $content = File::get($configPath);
        $presetRequire = "require('./vendor/taba/crm/tailwind-preset.js')";

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
                    '/(module\.exports\s*=\s*{)/',
                    "$1\n    presets: [{$presetRequire}],",
                    $content
                );
            }
            File::put($configPath, $newContent);
        }
    }

    protected function updateViteConfig(): void
    {
        $configPath = base_path('vite.config.js');
        if (!File::exists($configPath)) {
             $this->warn('vite.config.js not found. Please add the CRM theme path manually.');
            return;
        }

        $viteThemePath = "'vendor/taba/crm/src/resources/css/admin.css'";
        $content = File::get($configPath);

        if (!str_contains($content, $viteThemePath)) {
            $newContent = str_replace(
                'input: [',
                "input: [\n                {$viteThemePath},",
                $content
            );
            File::put($configPath, $newContent);
        }
    }
}
