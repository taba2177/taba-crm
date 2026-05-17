<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Taba\Crm\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'taba@admin.com'],
            [
                'name' => 'admin',
                'password' => Hash::make('admin'),
            ]
        );

        $user->assignRole('super_admin');
    }
}
