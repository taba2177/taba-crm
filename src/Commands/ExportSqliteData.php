<?php
// app/Console/Commands/ExportSqliteData.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ExportSqliteData extends Command
{
    protected $signature = 'db:export-sqlite';
    protected $description = 'Exports post_categories and posts data from the current DB to PHP seeder array files.';

    public function handle(): int
    {
        $this->info('Starting data export from SQLite...');

        $tables = [
            'post_categories',
            'posts',
        ];

        $dataDir = database_path('seeders/data');
        if (!File::isDirectory($dataDir)) {
            File::makeDirectory($dataDir);
        }

        foreach ($tables as $table) {
            $this->line("Exporting {$table}...");

            // 1. Fetch all data from the table
            $data = DB::table($table)->get()->toArray();

            if (empty($data)) {
                $this->warn("No data found in {$table}. Skipping.");
                continue;
            }

            // 2. Convert the Eloquent objects to simple arrays
            $dataArray = array_map(function ($item) {
                return (array) $item;
            }, $data);

            // 3. Format as a PHP return array string
            $phpContent = "<?php\n\nreturn " . var_export($dataArray, true) . ";\n";

            // 4. Save the data to the dedicated file
            $filePath = "{$dataDir}/{$table}.php";
            File::put($filePath, $phpContent);

            $this->info("Successfully exported {$table} to {$filePath}");
        }

        $this->info('Data export complete. You can now use php artisan migrate:fresh --seed.');
        return 0;
    }
}
