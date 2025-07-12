<?php

namespace Taba\Crm\Database\Seeders;

use Taba\Crm\Models\Post;
use Taba\Crm\Models\PostCategory;
use Taba\Crm\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //user
        $this->call(UserSeeder::class);


        $this->call(NewSiteSeeder::class);


        // Notification::make()
        //     ->title('Welcome to Filament')
        //     ->body('You are ready to start building your application.')
        //     ->success()
        //     ->sendToDatabase(User::first());
    }
}