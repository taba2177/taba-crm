<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;

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
            $roleName = config('filament-shield.super_admin.name', 'super_admin');
            
            // Ensure the role exists
            if (!Role::where('name', $roleName)->exists()) {
                Role::create(['name' => $roleName, 'guard_name' => 'web']);
            }

            if (!$existingUser->hasRole($roleName)) {
                $existingUser->assignRole($roleName);
                $this->command->info("Assigned {$roleName} role to existing user");
            }
            return;
        }

        // Create super admin user using App\Models\User. Use ::create() rather
        // than ::factory() so the seeder doesn't depend on a UserFactory whose
        // namespace varies by host project (Database\Factories vs custom).
        $user = User::create([
            'name' => 'Taba Admin',
            'email' => 'taba@admin.com',
            'password' => Hash::make('admin'),
            'email_verified_at' => now(),
        ]);

        $this->command->info('Created super admin user: taba@admin.com');

        // Assign super_admin role (Shield uses super_admin with underscore)
        $roleName = config('filament-shield.super_admin.name', 'super_admin');
        
        // Ensure the role exists
        if (!Role::where('name', $roleName)->exists()) {
            Role::create(['name' => $roleName, 'guard_name' => 'web']);
        }

        $user->assignRole($roleName);
        $this->command->info("Assigned {$roleName} role to user");
    }
}
