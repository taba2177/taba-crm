<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\SchemaOrg\Airline;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(ShieldSeeder::class);

        $this->call(UserSeeder::class);

        $this->call(AISiteSeeder::class);

    }
}
