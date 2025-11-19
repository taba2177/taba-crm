<?php

namespace Taba\Crm\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Throwable;

class InstallCommand extends Command
{
    protected $signature = 'crm:install {--publish-views : Publish the package views to the application} {--skip-frontend : Skip npm install/build and frontend configuration}';
    protected $description = 'Install all assets and configurations for the Taba CRM package.';

    public function handle(): int
    {
        $this->info('🚀 Starting Taba CRM installation...');

        // Check for required dependencies
        if (!$this->checkDependencies()) {
            return self::FAILURE;
        }

        // Run installation tasks
        if (!$this->task('Running dependency installers', fn() => $this->installDependencies())) return self::FAILURE;
        if (!$this->task('Running database migrations', fn() => $this->runMigrations())) return self::FAILURE;
        if (!$this->task('Publishing database assets (seeders, factories)', fn() => $this->publishDatabaseAssets())) return self::FAILURE;
        if (!$this->task('Setting up Filament Shield', fn() => $this->setupFilamentShield())) return self::FAILURE;
        if (!$this->task('Seeding database with super admin', fn() => $this->runSeeder())) return self::FAILURE;
        if (!$this->task('Updating User model for Shield', fn() => $this->updateUserModel())) return self::FAILURE;
        // Always publish essential views (logo component needed for Filament)
        if (!$this->task('Publishing essential views', fn() => $this->publishEssentialViews())) return self::FAILURE;
        // Publish all package views
        if (!$this->task('Publishing package views', fn() => $this->publishViews())) return self::FAILURE;
        // Update package.json before running npm so new devDependencies are installed.
        if (!$this->task('Updating package.json', fn() => $this->updateNodeDependencies())) return self::FAILURE;
        if (!$this->task('Ensuring app.css is compatible', fn() => $this->ensureAppCssCompatible())) return self::FAILURE;
        if (!$this->task('Publishing CSS resources', fn() => $this->publishCssResources())) return self::FAILURE;
        if (!$this->task('Configuring Tailwind CSS', fn() => $this->updateTailwindConfig())) return self::FAILURE;
        if (!$this->task('Configuring Vite', fn() => $this->updateViteConfig())) return self::FAILURE;
        if (!$this->task('Ensuring PostCSS is configured', fn() => $this->updatePostCssConfig())) return self::FAILURE;

        if (! $this->option('skip-frontend')) {
            if (!$this->task('Installing NPM packages', fn() => $this->runNpmInstall())) return self::FAILURE;
            if (!$this->task('Publishing package assets', fn() => $this->publishAssets())) return self::FAILURE;
            if (!$this->task('Building frontend assets', fn() => $this->runNpmBuild())) return self::FAILURE;
        } else {
            // When skipping frontend steps, still publish server-side assets.
            if (!$this->task('Publishing package assets', fn() => $this->publishAssets())) return self::FAILURE;
            $this->info('Skipped frontend tasks (--skip-frontend).');
        }

        $this->info('✅ Taba CRM installed successfully!');
        $this->newLine();
        $this->info('📧 Super Admin Credentials:');
        $this->info('   Email: taba@admin.com');
        $this->info('   Password: admin');
        $this->newLine();
        $this->warn('Final step: Please add `->plugin(\Taba\Crm\CrmPlugin::make())` to your AdminPanelProvider to activate the plugin.');
        $this->warn('And run `npm run build` again.');

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

    /**
     * Check for required dependencies before installation.
     */
    protected function checkDependencies(): bool
    {
        $this->info('🔍 Checking for required dependencies...');

        if (!class_exists('Filament\Panel')) {
            $this->error('Filament is not installed. Please install Filament before proceeding.');
            return false;
        }

        if (!class_exists('Awcodes\Curator\CuratorPlugin')) {
            $this->error('Curator plugin is not installed. Please install the Curator plugin before proceeding.');
            return false;
        }

        return true;
    }

    protected function installDependencies(): bool
    {
        // If the application already has a Filament AdminPanelProvider, do not
        // run the panel creation step to avoid overwriting or duplicating
        // the provider. We prefer to keep the developer's existing panels
        // intact. If no provider exists, create one.
        $providerPath = app_path('Providers/Filament/AdminPanelProvider.php');
        $providerClass = '\\App\\Providers\\Filament\\AdminPanelProvider';

        $isNewPanel = false;
        if (! File::exists($providerPath) && ! class_exists($providerClass)) {
            $this->info('No existing Filament admin panel detected — creating one.');
            $this->call('filament:install', ['--panels' => true, '--no-interaction' => true]);
            $isNewPanel = true;
        } else {
            $this->info('Detected existing Filament admin panel — skipping panel creation.');
        }

        // Update AdminPanelProvider to add CRM plugin and remove redundant methods
        if ($isNewPanel && File::exists($providerPath)) {
            $this->updateAdminPanelProvider($providerPath);
            // Clear config cache so the updated provider is loaded
            $this->call('config:clear');
            $this->call('route:clear');

            // Force reload the provider
            if (class_exists(\App\Providers\Filament\AdminPanelProvider::class)) {
                \Filament\Facades\Filament::setCurrentPanel(\Filament\Facades\Filament::getPanel('admin'));
            }
        }

        // Install curator regardless (it is safe to run multiple times).
        $this->call('curator:install', ['--no-interaction' => true]);

        if (class_exists(\App\Providers\Filament\AdminPanelProvider::class)) {
            \Filament\Facades\Filament::setCurrentPanel(\Filament\Facades\Filament::getPanel('admin'));
        }

        $this->call('vendor:publish', ['--tag' => 'filament-peek-assets', '--force' => true]);

        return true;
    }

    protected function updateAdminPanelProvider(string $providerPath): void
    {
        $content = File::get($providerPath);

        // Remove ->default() as it's already in the plugin
        $content = preg_replace('/\s*->default\(\)\s*\n/', "\n", $content);

        // Remove ->login() as it's already in the plugin
        $content = preg_replace('/\s*->login\(\)\s*\n/', "\n", $content);

        // Add CRM plugin before ->colors() or ->discoverResources()
        if (!str_contains($content, 'CrmPlugin::make()')) {
            // Add use statement if not present
            if (!str_contains($content, 'use Taba\Crm\CrmPlugin;')) {
                $content = preg_replace(
                    '/(namespace\s+App\\\\Providers\\\\Filament;)\s*\n/',
                    "$1\n\nuse Taba\\Crm\\CrmPlugin;\n",
                    $content
                );
            }

            // Add plugin before ->colors() or other configuration methods
            $patterns = [
                '/(\n\s+return \$panel\n\s+->id\([^)]+\)\n\s+->path\([^)]+\))/' => "$1\n            ->plugin(CrmPlugin::make())",
            ];

            foreach ($patterns as $pattern => $replacement) {
                if (preg_match($pattern, $content)) {
                    $content = preg_replace($pattern, $replacement, $content);
                    break;
                }
            }
        }

        File::put($providerPath, $content);
        $this->info('Updated AdminPanelProvider with CRM plugin.');
    }

    protected function publishDatabaseAssets(): bool
    {
        $this->call('vendor:publish', ['--tag' => 'crm-database', '--force' => true]);

        // Fix namespace in CrmSettingsSeeder after publishing
        $seederPath = database_path('seeders/CrmSettingsSeeder.php');
        if (File::exists($seederPath)) {
            $content = File::get($seederPath);
            $content = str_replace(
                'namespace Taba\Crm\Database\Seeders;',
                'namespace Database\Seeders;',
                $content
            );
            File::put($seederPath, $content);
        }

        return true;
    }

    protected function publishAssets(): bool
    {
        $this->call('vendor:publish', ['--tag' => 'crm-config']);
        // Database assets already published before seeding, so skip here
        return true;
    }

    protected function publishViews(): bool
    {
        $this->call('vendor:publish', ['--tag' => 'crm-views', '--force' => true]);
        $this->info('Views have been published successfully.');
        return true;
    }

    protected function publishEssentialViews(): bool
    {
        // Publish the logo component which is required for Filament
        $logoSource = __DIR__ . '/../views/components/logo.blade.php';
        $logoDestination = resource_path('views/components/logo.blade.php');

        if (!File::exists(dirname($logoDestination))) {
            File::makeDirectory(dirname($logoDestination), 0755, true);
        }

        File::copy($logoSource, $logoDestination);
        
        // Publish Arabic translation file
        $langSource = __DIR__ . '/../../lang/ar.json';
        $langDestination = lang_path('ar.json');
        
        if (!File::exists(dirname($langDestination))) {
            File::makeDirectory(dirname($langDestination), 0755, true);
        }
        
        if (File::exists($langSource)) {
            File::copy($langSource, $langDestination);
        }
        
        return true;
    }

    protected function publishCssResources(): bool
    {
        $this->call('vendor:publish', ['--tag' => 'resources', '--force' => true]);

        // Copy vendor CSS files that are imported by admin.css
        $cssDir = resource_path('css');
        $vendorCssFiles = [
            'vendor/filament/filament/resources/css/index.css' => 'index.css',
            'vendor/filament/filament/resources/css/base.css' => 'base.css',
            'vendor/filament/filament/resources/css/theme.css' => 'theme.css',
            'vendor/awcodes/filament-curator/resources/css/plugin.css' => 'curator-plugin.css',
            'vendor/pboivin/filament-peek/resources/css/plugin.css' => 'peek-plugin.css',
        ];

        foreach ($vendorCssFiles as $source => $dest) {
            $sourcePath = base_path($source);
            $destPath = $cssDir . '/' . $dest;

            if (File::exists($sourcePath)) {
                File::copy($sourcePath, $destPath);
            }
        }

        return true;
    }

    protected function runMigrations(): bool
    {
        return $this->call('migrate') === 0;
    }

    protected function runSeeder(): bool
    {
        // Run the full DatabaseSeeder which includes roles, user, categories, and posts
        return $this->call('db:seed', ['--class' => 'Database\\Seeders\\DatabaseSeeder']) === 0;
    }

    protected function updateUserModel(): bool
    {
        $userModelPath = app_path('Models/User.php');

        if (!File::exists($userModelPath)) {
            $this->warn('User model not found at: ' . $userModelPath);
            return false;
        }

        $content = File::get($userModelPath);

        // Check if already extending Taba\Crm\Models\User
        if (str_contains($content, 'extends \Taba\Crm\Models\User')) {
            return true; // Already updated
        }

        // Add import for Taba\Crm\Models\User
        if (!str_contains($content, 'use Taba\Crm\Models\User as CrmUser')) {
            $content = preg_replace(
                '/(namespace App\\\\Models;)/',
                "$1\n\nuse Taba\Crm\Models\User as CrmUser;",
                $content
            );
        }

        // Replace "extends Authenticatable" with "extends CrmUser"
        $content = preg_replace(
            '/(class User extends) Authenticatable/',
            '$1 CrmUser',
            $content
        );

        // Remove Authenticatable import since we're now extending CrmUser
        $content = preg_replace(
            '/use Illuminate\\\\Foundation\\\\Auth\\\\User as Authenticatable;\n/',
            '',
            $content
        );

        File::put($userModelPath, $content);

        $this->info('   User model updated to extend Taba\Crm\Models\User');
        return true;
    }

    protected function setupFilamentShield(): bool
    {
        // Install Filament Shield for admin panel
        $installResult = $this->call('shield:install', ['panel' => 'admin']);

        if ($installResult !== 0) {
            $this->error('Failed to install Filament Shield');
            return false;
        }

        // Generate policies and permissions for all resources
        $generateResult = $this->call('shield:generate', [
            '--all' => true,
            '--panel' => 'admin',
        ]);

        if ($generateResult !== 0) {
            $this->error('Failed to generate Shield permissions');
            return false;
        }

        return true;
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
            'preline' => '^3.2.3',
            'flowbite' => '^2.5.2',
        ];

        $packageJson = json_decode(File::get(base_path('package.json')), true);

        // Remove Tailwind v4 plugin if it exists (not compatible with v3)
        if (isset($packageJson['devDependencies']['@tailwindcss/vite'])) {
            unset($packageJson['devDependencies']['@tailwindcss/vite']);
            $this->info('Removed @tailwindcss/vite (Tailwind v4 plugin) - using Tailwind v3 instead.');
        }

        // Add or update devDependencies
        foreach ($packages as $package => $version) {
            $packageJson['devDependencies'][$package] = $version;
        }

        File::put(base_path('package.json'), json_encode($packageJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        return true;
    }

    protected function ensureAppCssCompatible(): bool
    {
        $appCssPath = resource_path('css/app.css');

        if (!File::exists($appCssPath)) {
            // If app.css doesn't exist, create one with Tailwind v3 syntax
            $cssContent = <<<'EOT'
@tailwind base;
@tailwind components;
@tailwind utilities;
EOT;
            File::ensureDirectoryExists(resource_path('css'));
            File::put($appCssPath, $cssContent);
            $this->info('Created resources/css/app.css with Tailwind v3 syntax.');
            return true;
        }

        $content = File::get($appCssPath);

        // Check if using Tailwind v4 syntax and convert to v3
        if (str_contains($content, "@import 'tailwindcss'") || str_contains($content, '@import "tailwindcss"')) {
            // Replace Tailwind v4 syntax with v3 syntax
            $newContent = preg_replace(
                "/@import\s+['\"]tailwindcss['\"];?/",
                "@tailwind base;\n@tailwind components;\n@tailwind utilities;",
                $content
            );

            // Remove v4-specific directives
            $newContent = preg_replace("/@source\s+.+;/", '', $newContent);
            $newContent = preg_replace("/@theme\s*\{[^}]+\}/s", '', $newContent);

            // Add Cairo font if not present
            if (!str_contains($newContent, 'Cairo')) {
                $newContent = '@import url("https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap");' . "\n" . $newContent;
            }

            // Clean up extra whitespace
            $newContent = preg_replace("/\n{3,}/", "\n\n", trim($newContent));

            File::put($appCssPath, $newContent);
            $this->info('Converted app.css from Tailwind v4 to v3 syntax.');
        }

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
        'postcss-nesting': {},
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
            $content = "import defaultTheme from 'tailwindcss/defaultTheme';\n\n module.exports = {\n    presets: [require('{$presetPath}')],\n    content: [\n        './app/Filament/**/*.php',\n        './resources/views/filament/**/*.blade.php',\n   './resources/views/**/*.blade.php', \n     './vendor/filament/**/*.blade.php',\n        './vendor/taba/crm/resources/views/**/*.blade.php', // Add crm views\n    ],\n};\n";
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

    protected function updateViteConfig(): void
    {
        $configPath = base_path('vite.config.js');
        $adminCssPath = 'vendor/taba/crm/src/resources/css/admin.css';

        // If vite.config.js does not exist, create it from a standard Laravel stub.
        if (! File::exists($configPath)) {
            $this->info('vite.config.js not found. Creating a new one for you...');
            $stub = <<<EOT
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                '{$adminCssPath}',
            ],
            refresh: true,
        }),
    ],
});
EOT;
            File::put($configPath, $stub);
            $this->info('Created vite.config.js with CRM admin.css.');
            return;
        }

        // Now that we know the file exists, remove Tailwind v4 plugin if present
        $content = File::get($configPath);

        // Remove @tailwindcss/vite import and usage
        if (str_contains($content, '@tailwindcss/vite')) {
            $content = preg_replace("/import\s+tailwindcss\s+from\s+['\"]@tailwindcss\/vite['\"];\s*/", '', $content);
            $content = preg_replace("/,?\s*tailwindcss\(\)\s*,?/", '', $content);
            File::put($configPath, $content);
            $this->info('Removed @tailwindcss/vite from vite.config.js');
        }

        // Reload content after potential modifications
        $content = File::get($configPath);

        // Replace old vendor path with new resources path if it exists
        $oldPath = 'vendor/taba/crm/src/resources/css/admin.css';
        if (str_contains($content, $oldPath)) {
            $content = str_replace($oldPath, $adminCssPath, $content);
            File::put($configPath, $content);
            $this->info('Updated admin.css path in vite.config.js from vendor to resources.');
        }

        // Add admin.css if not present - using multiple patterns to match different formatting styles
        if (! str_contains($content, $adminCssPath)) {
            // Try to match different input array patterns
            $patterns = [
                // Pattern 1: ['resources/css/app.css', 'resources/js/app.js']
                "/input:\s*\[(.*?'resources\/js\/app\.js')\s*\]/s" => "input: [$1, '{$adminCssPath}']",
                // Pattern 2: input: ['resources/css/app.css', 'resources/js/app.js'],
                "/(input:\s*\[.*?'resources\/js\/app\.js')/s" => "$1, '{$adminCssPath}'",
            ];

            $replaced = false;
            foreach ($patterns as $pattern => $replacement) {
                $newContent = preg_replace($pattern, $replacement, $content, 1, $count);
                if ($count > 0) {
                    File::put($configPath, $newContent);
                    $this->info('CRM admin.css added to vite.config.js.');
                    $replaced = true;
                    break;
                }
            }

            if (!$replaced) {
                $this->warn('Could not automatically add admin.css to vite.config.js. Please add it manually: ' . $adminCssPath);
            }
        }
    }
}
