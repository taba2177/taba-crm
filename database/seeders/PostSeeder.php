<?php

namespace Database\Seeders;
// database/seeders/PostSeeder.php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        // Check if posts already exist
        $existingCount = DB::table('posts')->count();
        if ($existingCount > 0) {
            $this->command->info("⏭️  Skipped: Posts table already has {$existingCount} records. Keeping existing data.");
            return;
        }

        $posts = require database_path('seeders/data/posts.php');

        $driver = DB::connection()->getDriverName();

        // 1. Disable Foreign Key Checks (Crucial if a related table is missing)
        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        } elseif ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        // 2. Insert the data (no truncate to preserve existing data)
        DB::table('posts')->insert($posts);

        // 3. Re-enable Foreign Key Checks
        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        } elseif ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $this->command->info('✅ Posts seeded.');
    }
}
