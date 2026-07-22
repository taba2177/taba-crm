<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roleName = config('filament-shield.super_admin.name', 'super_admin');

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);

        $user = User::firstOrCreate(
            ['email' => 'taba@admin.com'],
            [
                'name' => 'Taba Admin',
                'password' => Hash::make('admin'),
                'email_verified_at' => now(),
            ]
        );

        if (!$user->hasRole($roleName)) {
            $user->assignRole($roleName);
        }

        $this->command->info("Super admin ready: taba@admin.com ({$roleName})");
    }
}
