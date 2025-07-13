<?php

// FILE: packages/taba/crm/src/Commands/InstallCommand.php

namespace Taba\Crm\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InstallCommand extends Command
{
    protected $signature = 'crm:install';
    protected $description = 'Install all assets and configurations for the Taba CRM package.';

    public function handle()
    {
        $this->info('Installing Taba CRM package...');

        // Run setup commands for third-party dependencies.
        $this->comment('Publishing required assets for dependencies...');
        Artisan::call('vendor:publish', ['--tag' => 'filament-peek-assets', '--force' => true]);
        Artisan::call('curator:install');
        // Artisan::call('filament-breezy:install');

        // Publish this package's configuration file.
        $this->comment('Publishing package configuration...');
        Artisan::call('vendor:publish', ['--tag' => 'crm-config', '--force' => true]);

        // Run database migrations.
        $this->comment('Running database migrations...');
        Artisan::call('migrate');

        $this->info('Taba CRM installed successfully!');
        $this->warn('Please complete the final manual steps:');
        $this->line('1. Add `new \Taba\Crm\CrmPlugin()` to your AdminPanelProvider.');
        $this->line('2. Run `npm install && npm run dev` to compile frontend assets.');
    }
}