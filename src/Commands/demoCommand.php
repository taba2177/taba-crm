<?php

// FILE: packages/taba/crm/src/Commands/demoCommand.php

namespace Taba\Crm\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class demoCommand extends Command
{
    protected $signature = 'crm:demo';
    protected $description = 'demo all assets and configurations for the Taba CRM package.';

    public function handle()
    {

        $this->comment('Publishing package configuration...');
        Artisan::call('vendor:publish', ['--tag' => 'crm', '--force' => true,'--no-interaction' => true
    ]);   // Run database migrations

        $this->info('Taba CRM demo successfully!');
    }
}
