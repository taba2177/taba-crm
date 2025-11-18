<?php

namespace Database\Seeders;
// database/seeders/PostSeeder.php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = require database_path('seeders/data/posts.php');

        $driver = DB::connection()->getDriverName();

        // 1. Disable Foreign Key Checks (Crucial if a related table is missing)
        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        } elseif ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        // 2. Truncate the posts table (This is where the trigger fails)
        DB::table('posts')->truncate();

        // 3. Insert the data
        DB::table('posts')->insert($posts);

        // 4. Re-enable Foreign Key Checks
        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        } elseif ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $this->command->info('✅ Posts seeded.');
    }
}