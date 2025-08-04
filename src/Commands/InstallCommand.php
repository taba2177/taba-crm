<?php

namespace Taba\Crm\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Throwable;

class InstallCommand extends Command
{
    protected $signature = 'crm:install';
    protected $description = 'Install all assets and configurations for the Taba CRM package.';

    public function handle(): int
    {
        $this->info('🚀 Starting Taba CRM installation...');

        if (!$this->task('Running dependency installers', fn() => $this->installDependencies())) return self::FAILURE;
        if (!$this->task('Running database migrations', fn() => $this->runMigrations())) return self::FAILURE;
        if (!$this->task('Installing NPM packages', fn() => $this->runNpmInstall())) return self::FAILURE;
        if (!$this->task('Publishing package assets', fn() => $this->publishAssets())) return self::FAILURE;
        if (!$this->task('Updating package.json', fn() => $this->updateNodeDependencies())) return self::FAILURE;
        if (!$this->task('Configuring Tailwind CSS', fn() => $this->updateTailwindConfig())) return self::FAILURE;
        if (!$this->task('Configuring Vite', fn() => $this->updateViteConfig())) return self::FAILURE;
        if (!$this->task('Ensuring PostCSS is configured', fn() => $this->updatePostCssConfig())) return self::FAILURE;
        if (!$this->task('Running database migrations', fn() => $this->runMigrations())) return self::FAILURE;
        if (!$this->task('Installing NPM packages', fn() => $this->runNpmInstall())) return self::FAILURE;
        if (!$this->task('Building frontend assets', fn() => $this->runNpmBuild())) return self::FAILURE;

        $this->info('✅ Taba CRM installed successfully!');
        $this->warn('Final step: Please add `->plugin(\Taba\Crm\CrmPlugin::make())` to your AdminPanelProvider to activate the plugin.');
        $this->warn('And Run npm run build again');

        return self::SUCCESS;
    }

    /**
     * Executes a task and reports its success or failure.
     */
    protected function task(string $description, callable $task): bool
    {
        $this->output->write($description . '...');

        try {
            if ($task() !== false) {
                $this->output->writeln(' <info>✔</info>');
                return true;
            }
        } catch (Throwable $e) {
             $this->error($e->getMessage());
        }

        $this->output->writeln(' <error>✘</error>');
        return false;
    }

    protected function installDependencies(): bool
    {
        $this->call('filament:install', ['--panels' => true, '--no-interaction' => true]);
        $this->call('curator:install', ['--no-interaction' => true]);

        if (class_exists(\App\Providers\Filament\AdminPanelProvider::class)) {
            \Filament\Facades\Filament::setCurrentPanel(\Filament\Facades\Filament::getPanel('admin'));
        }

        $this->call('vendor:publish', ['--tag' => 'filament-peek-assets', '--force' => true]);
        return true;
    }

    protected function publishAssets(): bool
    {
        $this->call('vendor:publish', ['--tag' => 'crm-config']);
        // , '--force' => true]);
        $this->call('vendor:publish', ['--tag' => 'crm-database']);
        // , '--force' => true]);
        return true;
    }

    protected function runMigrations(): bool
    {
        return $this->call('migrate') === 0;
    }

    protected function runNpmInstall(): bool
    {
        $result = Process::run('npm install');
        if (!$result->successful()) {
            $this->error($result->errorOutput());
        }
        return $result->successful();
    }

    protected function runNpmBuild(): bool
    {
        $result = Process::run('npm run build');
        if (!$result->successful()) {
            $this->error($result->errorOutput());
        }
        return $result->successful();
    }

    protected function updateNodeDependencies(): bool
    {
        if (!File::exists(base_path('package.json'))) {
            $this->warn('package.json not found. Skipping dependency update.');
            return true; // Not a failure, just a skip
        }

        $packages = [
            'tailwindcss' => '^3.4.0',
            'postcss' => '^8.4.38',
            'autoprefixer' => '^10.4.19',
            'vite' => '^7.0.0',
            'laravel-vite-plugin' => '^2.0.0',
            '@tailwindcss/forms' => '^0.5.7',
            '@tailwindcss/typography' => '^0.5.10',
            'postcss-nesting' => '^12.1.5',
            'cropperjs' => '^1.6.2',
        ];

        $packageJson = json_decode(File::get(base_path('package.json')), true);

        // Add or update devDependencies
        foreach ($packages as $package => $version) {
            $packageJson['devDependencies'][$package] = $version;
        }

        File::put(base_path('package.json'), json_encode($packageJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        return true;
    }
    protected function updatePostCssConfig(): bool
{
    $configPath = base_path('postcss.config.cjs');

    if (File::exists($configPath)) {
        return true; // Assume user has their own config if it already exists.
    }

    $stub = <<<'EOT'
module.exports = {
    plugins: {
        "tailwindcss/nesting": {},
        tailwindcss: {},
        autoprefixer: {},
    },
};
EOT;

    File::put($configPath, $stub);

    return true;
}

   protected function updateTailwindConfig(): void
    {
        $configPath = base_path('tailwind.config.js');
        $presetPath = './vendor/taba/crm/tailwind-preset.js'; // Correct path to preset

        if (!File::exists($configPath)) {
            // If tailwind.config.js doesn't exist, create a new one using the preset.
            $content = "import defaultTheme from 'tailwindcss/defaultTheme';
\n module.exports = {\n    presets: [require('{$presetPath}')],\n    content: [\n        './app/Filament/**/*.php',\n        './resources/views/filament/**/*.blade.php',\n   './resources/views/**/*.blade.php', \n     './vendor/filament/**/*.blade.php',\n        './packages/taba/crm/resources/views/**/*.blade.php', // Add crm views\n    ],\n};\n";
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
