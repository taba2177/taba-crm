<?php

namespace Taba\Crm\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Process;

class InstallCommand extends Command
{
    protected $signature = 'crm:install';
    protected $description = 'Install all assets and configurations for the Taba CRM package.';

    public function handle()
    {
        $this->info('Installing Taba CRM package...');

        // Install Filament
        $this->info('Installing AdminPanelProvider...');
        Artisan::call('filament:install', [
            '--panels' => true,
            '--no-interaction' => true
        ]);

        // Install Tailwind CSS
        $this->info('Setting up Tailwind CSS...');
        $this->installTailwind();

        // Run setup commands for third-party dependencies
        $this->comment('Publishing required assets for dependencies...');
        Artisan::call('vendor:publish', ['--tag' => 'filament-peek-assets', '--force' => true, '--no-interaction' => true]);
        Artisan::call('curator:install', ['--no-interaction' => true]);

        $this->comment('Publishing breezy dependencies...');
        Artisan::call('breezy:install', ['--no-interaction' => true]);

        // Publish package assets
        $this->publishPackageAssets();

        // Run database migrations
        $this->runDatabaseOperations();

        $this->info('Taba CRM installed successfully!');
        $this->showFinalInstructions();
    }

    protected function installTailwind()
    {
        $this->info('Installing Tailwind CSS dependencies...');
        Process::run('npm install -D tailwindcss@latest postcss@latest autoprefixer@latest @tailwindcss/vite@latest');

        $this->info('Initializing Tailwind CSS...');
        Process::run('npx tailwindcss init -p');

        // You might want to modify the tailwind.config.js here
        $this->modifyTailwindConfig();
    }

    protected function modifyTailwindConfig()
    {
        $configPath = base_path('tailwind.config.js');
        $configContent = <<<'EOT'
        /** @type {import('tailwindcss').Config} */
        module.exports = {
          content: [
            "./resources/**/*.blade.php",
            "./resources/**/*.js",
            "./resources/**/*.vue",
            "./vendor/taba/crm/**/*.blade.php",
          ],
          theme: {
            extend: {},
          },
          plugins: [],
        }
        EOT;

        file_put_contents($configPath, $configContent);
        $this->info('Tailwind CSS configuration updated.');
    }

    protected function publishPackageAssets()
    {
        $this->comment('Publishing package configuration...');
        Artisan::call('vendor:publish', ['--tag' => 'crm-config', '--force' => true, '--no-interaction' => true]);
        Artisan::call('vendor:publish', ['--tag' => 'crm-database', '--force' => true, '--no-interaction' => true]);
        $this->comment('Publishing views...');
        Artisan::call('vendor:publish', ['--tag' => 'views', '--force' => true, '--no-interaction' => true]);
    }

    protected function runDatabaseOperations()
    {
        $this->comment('Running database migrations...');
        Artisan::call('migrate', ['--no-interaction' => true]);
        $this->comment('Running database seeder...');
        Artisan::call('db:seed', ['--no-interaction' => true]);
    }

    protected function showFinalInstructions()
    {
        $this->warn('Please complete the final manual steps:');
        $this->line('1. Add `->plugins([\Taba\Crm\CrmPlugin::make()])` to your AdminPanelProvider.');
        $this->line('2. Run `npm install && npm run dev` to compile frontend assets.');
    }
}
