<?php

namespace Database\Seeders;
// database/seeders/PostSeeder.php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $truncate = false; // Set to true to truncate, false to keep existing data
        // Check if posts already exist
        $existingCount = DB::table('posts')->count();
        if ($existingCount > 0 && ! $truncate) {
            $this->command->info("⏭️  Skipped: Posts table already has {$existingCount} records. Keeping existing data.");
            return;
        }

        $posts = require database_path('seeders/data/posts.php');

        // Ensure every row has a user_id — posts.user_id is NOT NULL.
        // Default to the first user (super admin seeded just before this seeder).
        $defaultUserId = DB::table('users')->value('id') ?? 1;
        foreach ($posts as &$row) {
            if (! array_key_exists('user_id', $row) || $row['user_id'] === null) {
                $row['user_id'] = $defaultUserId;
            }
        }
        unset($row);

        $driver = DB::connection()->getDriverName();

        // 1. Disable Foreign Key Checks (Crucial if a related table is missing)
        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        } elseif ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        if ($truncate) {
            DB::table('posts')->truncate();
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
