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

    protected array $errors = [];
    protected array $warnings = [];

    public function handle(): int
    {
        $this->info('🚀 Starting Taba CRM installation...');

        // Check for required dependencies
        if (!$this->checkDependencies()) {
            return self::FAILURE;
        }

        // Run installation tasks - collect errors but continue
        $this->task('Running dependency installers', fn() => $this->installDependencies());
        $this->task('Running database migrations', fn() => $this->runMigrations());
        $this->task('Publishing database assets (seeders, factories)', fn() => $this->publishDatabaseAssets());
        // Update User model BEFORE Shield setup so it has the HasRoles trait
        $this->task('Updating User model for Shield', fn() => $this->updateUserModel());
        // Always publish essential views (logo component needed for Filament)
        $this->task('Publishing essential views', fn() => $this->publishEssentialViews());
        // Publish all package views
        $this->task('Publishing package views', fn() => $this->publishViews());
        // Update package.json before running npm so new devDependencies are installed.
        $this->task('Updating package.json', fn() => $this->updateNodeDependencies());
        $this->task('Ensuring app.css is compatible', fn() => $this->ensureAppCssCompatible());
        $this->task('Publishing CSS resources', fn() => $this->publishCssResources());
        $this->task('Configuring Tailwind CSS', fn() => $this->updateTailwindConfig());
        $this->task('Configuring Vite', fn() => $this->updateViteConfig());
        $this->task('Ensuring PostCSS is configured', fn() => $this->updatePostCssConfig());
        $this->task('Publishing Filament assets', fn() => $this->runFilamentAssets());


        if (! $this->option('skip-frontend')) {
            $this->task('Installing NPM packages', fn() => $this->runNpmInstall());
            $this->task('Publishing package assets', fn() => $this->publishAssets());
            $this->task('Building frontend assets', fn() => $this->runNpmBuild());
            $this->task('Publishing Angular frontend', fn() => $this->publishAngularFrontend());
            $angularInstallOk = $this->task('Installing Angular npm packages', fn() => $this->runAngularNpmInstall());

            if ($angularInstallOk) {
                $this->task('Building Angular frontend', fn() => $this->runAngularBuild());
            } else {
                $this->warnings[] = 'Skipping Angular build because Angular npm install did not complete successfully.';
            }
        } else {
            // When skipping frontend steps, still publish server-side assets.
            $this->task('Publishing package assets', fn() => $this->publishAssets());
            $this->info('Skipped frontend tasks (--skip-frontend).');
        }

        // Always generate discovery files
        $this->task('Generating llms.txt', fn() => $this->generateLlmsTxt());
        $this->task('Writing robots.txt', fn() => $this->writeRobotsTxt());

        // Run Shield setup while AdminPanelProvider still has ->default() (added by updateAdminPanelProvider)
        $this->task('Setting up Filament Shield', fn() => $this->setupFilamentShield());

        // Seed AFTER Shield creates roles and permissions
        $this->task('Seeding database with super admin', fn() => $this->runSeeder());

        // Now that Shield is done, remove ->default() since CrmPlugin sets it
        $this->task('Finalizing AdminPanelProvider configuration', fn() => $this->finalizeAdminPanelProvider());

        $this->displayResults();

        return empty($this->errors) ? self::SUCCESS : self::FAILURE;
    }

    // php artisan filament:assets
    protected function runFilamentAssets(): bool
    {
        return $this->call('filament:assets') === 0;
    }

    protected function displayResults(): void
    {
        $this->newLine();

        if (!empty($this->warnings)) {
            $this->warn('⚠️  Installation completed with warnings:');
            foreach ($this->warnings as $warning) {
                $this->warn('   • ' . $warning);
            }
            $this->newLine();
        }

        if (!empty($this->errors)) {
            $this->error('❌ Installation completed with errors:');
            foreach ($this->errors as $error) {
                $this->error('   • ' . $error);
            }
            $this->newLine();

            // Retry failed tasks after system is fully initialized
            $this->info('🔄 Retrying failed tasks after system initialization...');
            $this->retryFailedTasks();
        } else {
            $this->info('✅ Taba CRM installed successfully!');
        }

        $this->newLine();
        $this->info('📧 Super Admin Credentials:');
        $this->info('   Email: taba@admin.com');
        $this->info('   Password: admin');
        $this->newLine();

        if (empty($this->errors)) {
            $this->warn('Final step: Please add `->plugin(\Taba\Crm\CrmPlugin::make())` to your AdminPanelProvider to activate the plugin.');
            $this->warn('And run `npm run build` again if you skipped frontend tasks.');
        } else {
            $this->warn('Please resolve the above errors manually or run the installation command again.');
        }
    }

    protected function retryFailedTasks(): void
    {
        $retryableErrors = [];

        foreach ($this->errors as $error) {
            if (str_contains($error, 'Setting up Filament Shield')) {
                $retryableErrors[] = 'Shield setup';
            }
        }

        if (empty($retryableErrors)) {
            return;
        }

        // Clear caches to ensure updated providers are loaded
        $this->call('config:clear');
        $this->call('route:clear');
        $this->call('cache:clear');

        $this->newLine();
        $originalErrors = $this->errors;
        $this->errors = [];

        // Retry Shield setup if it failed
        if (in_array('Shield setup', $retryableErrors)) {
            $this->task('Retrying Filament Shield setup', fn() => $this->setupFilamentShield());
        }

        // If retry succeeded, remove the original error
        if (empty($this->errors)) {
            $this->newLine();
            $this->info('✅ All failed tasks completed successfully after retry!');
            $this->errors = [];
        } else {
            // Restore original errors if retry also failed
            $this->errors = array_merge($originalErrors, $this->errors);
        }
    }

    /**
     * Executes a task and reports its success or failure.
     * Now continues on failure and collects errors.
     */
    protected function task(string $description, callable $task): bool
    {
        $this->output->write($description . '...');

        try {
            $result = $task();
            if ($result !== false) {
                $this->output->writeln(' <info>✔</info>');
                return true;
            } else {
                $this->output->writeln(' <comment>⚠</comment>');
                $this->warnings[] = $description . ' returned false';
                return false;
            }
        } catch (Throwable $e) {
            $this->output->writeln(' <error>✘</error>');
            $this->errors[] = $description . ': ' . $e->getMessage();
            return false;
        }
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
            // Clear all caches so the updated provider is loaded
            $this->call('config:clear');
            $this->call('route:clear');
            $this->call('view:clear');
        }

        // Install curator regardless (it is safe to run multiple times).
        $this->call('curator:install', ['--no-interaction' => true]);

        $this->call('vendor:publish', ['--tag' => 'filament-peek-assets', '--force' => true]);

        return true;
    }

    protected function updateAdminPanelProvider(string $providerPath): void
    {
        $content = File::get($providerPath);

        // Remove ->login() as it's already in the plugin
        $content = preg_replace('/\s*->login\(\)\s*\n/', "\n", $content);

        // Ensure ->default() is present for Shield (will be removed later in finalizeAdminPanelProvider)
        if (!str_contains($content, '->default()')) {
            // Add ->default() after ->path()
            $content = preg_replace(
                '/(->path\([^)]+\))/',
                "$1\n            ->default()",
                $content,
                1
            );
        }

        // Add CRM plugin
        if (!str_contains($content, 'CrmPlugin::make()')) {
            // Add use statement if not present
            if (!str_contains($content, 'use Taba\Crm\CrmPlugin;')) {
                $content = preg_replace(
                    '/(namespace\s+App\\\\Providers\\\\Filament;)/',
                    "$1\n\nuse Taba\\Crm\\CrmPlugin;",
                    $content
                );
            }

            // Add plugin after ->path() or ->default()
            // This is more flexible and works with different formatting styles
            if (str_contains($content, '->default()')) {
                $content = preg_replace(
                    '/(->default\(\))/',
                    "$1\n            ->plugin(CrmPlugin::make())",
                    $content,
                    1
                );
            } else {
                $content = preg_replace(
                    '/(->path\([^)]+\))/',
                    "$1\n            ->plugin(CrmPlugin::make())",
                    $content,
                    1
                );
            }
        }

        File::put($providerPath, $content);
        $this->info('Updated AdminPanelProvider with CRM plugin.');
    }

    protected function finalizeAdminPanelProvider(): bool
    {
        $providerPath = app_path('Providers/Filament/AdminPanelProvider.php');

        if (!File::exists($providerPath)) {
            return true; // Nothing to finalize
        }

        $content = File::get($providerPath);

        // Remove ->default() since it's in the plugin (after Shield setup is complete)
        $content = preg_replace('/\s*->default\(\)\s*\n/', "\n", $content);

        File::put($providerPath, $content);

        return true;
    }

    protected function publishDatabaseAssets(): bool
    {
        // Check if data folder already exists and backup if needed
        $dataPath = database_path('seeders/data');
        $backupPath = null;

        if (File::exists($dataPath)) {
            // Create a temporary backup of existing data
            $backupPath = database_path('seeders/data_backup_' . time());
            File::copyDirectory($dataPath, $backupPath);
            $this->info('   📦 Backing up existing seed data...');
        }

        // Publish all database assets
        $this->call('vendor:publish', ['--tag' => 'crm-database', '--force' => true]);

        // If we had a backup, restore the original data folder
        if ($backupPath && File::exists($backupPath)) {
            // Remove the newly published data folder
            if (File::exists($dataPath)) {
                File::deleteDirectory($dataPath);
            }
            // Restore the backup
            File::moveDirectory($backupPath, $dataPath);
            $this->info('   ✓ Seeder classes updated, your existing seed data preserved');
        }

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
        // Publish the logo component which is required for Filament (only if it doesn't exist)
        $logoSource = __DIR__ . '/../views/components/logo.blade.php';
        $logoDestination = resource_path('views/components/logo.blade.php');

        if (!File::exists(dirname($logoDestination))) {
            File::makeDirectory(dirname($logoDestination), 0755, true);
        }

        // Only copy logo if it doesn't exist to preserve user customizations
        if (!File::exists($logoDestination)) {
            File::copy($logoSource, $logoDestination);
            $this->info('   ✓ Logo component published');
        } else {
            $this->info('   ⏭️  Logo component already exists, keeping your version');
        }

        // Merge Arabic translation file instead of overwriting
        $langSource = __DIR__ . '/../../lang/ar.json';
        $langDestination = lang_path('ar.json');

        if (!File::exists(dirname($langDestination))) {
            File::makeDirectory(dirname($langDestination), 0755, true);
        }

        if (File::exists($langSource)) {
            if (File::exists($langDestination)) {
                // Merge existing translations with new ones
                $existingTranslations = json_decode(File::get($langDestination), true) ?? [];
                $newTranslations = json_decode(File::get($langSource), true) ?? [];

                // Merge: existing translations take precedence, new ones are added
                $mergedTranslations = array_merge($newTranslations, $existingTranslations);

                File::put($langDestination, json_encode($mergedTranslations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                $this->info('   ✓ Arabic translations merged with existing translations');
            } else {
                // No existing file, just copy
                File::copy($langSource, $langDestination);
                $this->info('   ✓ Arabic translations published');
            }
        }

        return true;
    }

    protected function publishCssResources(): bool
    {
        $this->call('vendor:publish', ['--tag' => 'resources', '--force' => true]);

        // Copy vendor CSS files that are imported by admin.css (only if they don't exist)
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
                // Only copy if destination doesn't exist to preserve user modifications
                if (!File::exists($destPath)) {
                    File::copy($sourcePath, $destPath);
                } else {
                    // File exists, check if it's outdated (optional - just skip for safety)
                    $this->info('   ⏭️  ' . $dest . ' already exists, keeping your version');
                }
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
        // Ensure the admin panel provider is loaded and registered before Shield runs
        try {
            // Force reload the AdminPanelProvider
            if (class_exists(\App\Providers\Filament\AdminPanelProvider::class)) {
                $provider = app()->resolveProvider(\App\Providers\Filament\AdminPanelProvider::class);
                if (!$provider) {
                    $provider = new \App\Providers\Filament\AdminPanelProvider(app());
                    app()->register($provider);
                }
            }

            // Verify the admin panel exists and is set as default
            if (!\Filament\Facades\Filament::getDefaultPanel()) {
                $panels = \Filament\Facades\Filament::getPanels();
                if (isset($panels['admin'])) {
                    \Filament\Facades\Filament::setCurrentPanel($panels['admin']);
                }
            }
        } catch (\Exception $e) {
            $this->warn('Note: Could not pre-register panel: ' . $e->getMessage());
        }

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
        // Check if npm is available
        $npmCheck = Process::run('npm --version');
        if (!$npmCheck->successful()) {
            $this->warnings[] = 'npm command not found - Please install Node.js and npm, then run: npm install';
            return false;
        }

        $result = Process::run('npm install');
        if (!$result->successful()) {
            $this->errors[] = 'npm install failed: ' . $result->errorOutput();
            return false;
        }
        return true;
    }

    protected function runNpmBuild(): bool
    {
        // Check if npm is available
        $npmCheck = Process::run('npm --version');
        if (!$npmCheck->successful()) {
            $this->warnings[] = 'npm command not found - Please install Node.js and npm, then run: npm run build';
            return false;
        }

        $result = Process::timeout(900)->run('npm run build');
        if (!$result->successful()) {
            $this->errors[] = 'npm run build failed: ' . $result->errorOutput();
            return false;
        }
        return true;
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
            'axios' => '^1.7.0',
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
            // Create a backup before modifying
            $backupPath = resource_path('css/app.css.backup.' . time());
            File::copy($appCssPath, $backupPath);
            $this->info('   📦 Created backup of app.css at: css/app.css.backup.' . time());

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
            $this->warn('   Original app.css backed up. You can restore it if needed.');
        }

        return true;
    }

    protected function updatePostCssConfig(): bool
    {
        $configPath = base_path('postcss.config.cjs');

        if (File::exists($configPath)) {
            $this->info('   ⏭️  postcss.config.cjs already exists, keeping your configuration');
            return true; // User has their own config, don't override
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
        $this->info('   ✓ Created postcss.config.cjs');

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
        $oldPath = 'vendor/taba/crm/src/resources/css/admin.css'; // keep this to migrate old installs
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

    // -------------------------------------------------------------------------
    // Angular SPA helpers (Task 10)
    // -------------------------------------------------------------------------

    protected function publishAngularFrontend(): bool
    {
        $source = dirname(__DIR__, 2) . '/frontend';
        $dest = base_path('frontend');

        if (File::isDirectory($dest)) {
            $this->warnings[] = 'Angular frontend already exists at ' . $dest . ' - skipping copy (customize freely).';
            return true;
        }

        if (! File::isDirectory($source)) {
            $this->warnings[] = 'Package frontend/ directory not found — skipping Angular publish.';
            return true;
        }

        return File::copyDirectory($source, $dest);
    }

    protected function runAngularNpmInstall(): bool
    {
        $frontendPath = base_path('frontend');

        if (! File::isDirectory($frontendPath)) {
            $this->warnings[] = 'frontend/ not found — skipping Angular npm install.';
            return true;
        }

        $npmCheck = Process::run('npm --version');
        if (! $npmCheck->successful()) {
            $this->warnings[] = 'npm not found — run `cd frontend && npm install` manually.';
            return true;
        }

        // npm install commonly exceeds 60s in CI/Windows; allow a generous timeout.
        $result = Process::timeout(900)->path($frontendPath)->run('npm install');
        if (! $result->successful()) {
            $this->errors[] = 'Angular npm install failed: ' . $result->errorOutput();
            return false;
        }

        return true;
    }

    protected function runAngularBuild(): bool
    {
        $frontendPath = base_path('frontend');

        if (! File::isDirectory($frontendPath)) {
            $this->warnings[] = 'frontend/ not found — skipping Angular build.';
            return true;
        }

        $npmCheck = Process::run('npm --version');
        if (! $npmCheck->successful()) {
            $this->warnings[] = 'npm not found — run `cd frontend && npm run build` manually.';
            return true;
        }

        $result = Process::timeout(900)->path($frontendPath)->run('npm run build');
        if (! $result->successful()) {
            $buildOutput = trim($result->errorOutput() . "\n" . $result->output());
            $this->errors[] = 'Angular build failed: ' . $buildOutput;
            return false;
        }

        return true;
    }

    protected function generateLlmsTxt(): bool
    {
        try {
            $siteName    = \Taba\Crm\Models\CrmSetting::get('site_name', 'CRM Site');
            $siteDesc = \Taba\Crm\Models\CrmSetting::get('site_description', '');
        } catch (\Throwable) {
            $siteName    = config('app.name', 'CRM Site');
            $siteDesc = '';
        }

        $siteName    = is_array($siteName) ? ($siteName['en'] ?? reset($siteName)) : (string) $siteName;
        $siteDesc = is_array($siteDesc) ? ($siteDesc['en'] ?? reset($siteDesc)) : (string) $siteDesc;
        $base = url('/');
        $apiBase = url('/api/v1');

        $content = <<<LLMS
        # {$siteName}

        > {$siteDesc}

        This site is built on the taba/crm Laravel package. Content is available via a REST API.

        ## Key URLs

        - Homepage: {$base}/
        - Sitemap: {$base}/sitemap.xml
        - Posts API: {$apiBase}/posts
        - Categories API: {$apiBase}/categories

        ## Content negotiation

        Individual post pages support `Accept: text/markdown` for plain Markdown responses.
        Example: GET {$apiBase}/posts/{slug} with Accept: text/markdown

        ## Allowed bots

        All AI crawlers are permitted. See /robots.txt for details.
        LLMS;

        File::put(public_path('llms.txt'), preg_replace('/^        /m', '', $content));
        return true;
    }

    protected function writeRobotsTxt(): bool
    {
        $sitemapUrl = url('/sitemap.xml');
        $robotsPath = public_path('robots.txt');

        if (File::exists($robotsPath) && str_contains(File::get($robotsPath), 'Sitemap:')) {
            $this->warnings[] = 'robots.txt already has a Sitemap directive - skipping overwrite.';
            return true;
        }

        $content = <<<TXT
        User-agent: *
        Allow: /
        Disallow: /admin
        Disallow: /filament
        Disallow: /api/v1/actions
        Disallow: /preview/

        # AI crawlers - explicitly allowed
        User-agent: GPTBot
        Allow: /

        User-agent: ClaudeBot
        Allow: /

        User-agent: PerplexityBot
        Allow: /

        User-agent: anthropic-ai
        Allow: /

        User-agent: Applebot
        Allow: /

        User-agent: Googlebot-Extended
        Allow: /

        Sitemap: {$sitemapUrl}
        TXT;

        File::put($robotsPath, preg_replace('/^        /m', '', $content));
        return true;
    }
}
