<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
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
        // Check if user already exists
        $existingUser = User::where('email', 'taba@admin.com')->first();
        if ($existingUser) {
            $this->command->info('Super admin user already exists: taba@admin.com');

            // Assign role if not already assigned (Shield uses super_admin with underscore)
            $roleName = Role::where('name', 'super_admin')->exists() ? 'super_admin' : 'super-admin';
            if (!$existingUser->hasRole($roleName)) {
                $existingUser->assignRole($roleName);
                $this->command->info("Assigned {$roleName} role to existing user");
            }
            return;
        }

        // Create super admin user
        $user = User::factory()->create([
            'name' => 'Taba Admin',
            'email' => 'taba@admin.com',
            'password' => Hash::make('admin'),
        ]);

        $this->command->info('Created super admin user: taba@admin.com');

        // Assign super-admin role (Shield uses super_admin with underscore)
        $roleName = Role::where('name', 'super_admin')->exists() ? 'super_admin' : 'super-admin';
        if (Role::where('name', $roleName)->exists()) {
            $user->assignRole($roleName);
            $this->command->info("Assigned {$roleName} role to user");
        }
    }
}
