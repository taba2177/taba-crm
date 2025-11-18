<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Use the configured auth model (works for both host app and package)
        $userModel = config('auth.providers.users.model', \App\Models\User::class);
        
        // Create super admin user
        $user = $userModel::factory()->create([
            'name' => 'Taba Admin',
            'email' => 'taba@admin.com',
            'password' => Hash::make('admin'),
        ]);

        // Assign super-admin role if it exists
        if (Role::where('name', 'super-admin')->exists()) {
            $user->assignRole('super-admin');
        }
    }
}