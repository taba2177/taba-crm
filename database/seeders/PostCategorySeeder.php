<?php

namespace Database\Seeders;
// database/seeders/PostCategorySeeder.php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PostCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = require database_path('seeders/data/post_categories.php');

        $driver = DB::connection()->getDriverName();

        // 1. Disable Foreign Key Checks based on the database driver
        if ($driver === 'sqlite') {
            // SQLite uses PRAGMA
            DB::statement('PRAGMA foreign_keys = OFF;');
        } elseif ($driver === 'mysql' || $driver === 'mariadb') {
            // MySQL/MariaDB uses SET
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }
        // PostgreSQL does not require disabling for truncate/insert.

        // 2. Truncate the table
        DB::table('post_categories')->truncate();

        // 3. Insert the data
        DB::table('post_categories')->insert($categories);

        // 4. Re-enable Foreign Key Checks based on the database driver
        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        } elseif ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $this->command->info('✅ Post Categories seeded.');
    }
}