<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles and permissions first
        $this->call(RolesAndPermissionsSeeder::class);
        
        // Then create the user
        $this->call(UserSeeder::class);

        // $this->call(NewSiteSeeder::class);


        // Notification::make()
        //     ->title('Welcome to Filament')
        //     ->body('You are ready to start building your application.')
        //     ->success()
        //     ->sendToDatabase(User::first());
    }
}
