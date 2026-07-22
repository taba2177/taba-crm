<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'edit articles',
            'delete articles',
            'publish articles',
            'unpublish articles',
            'create posts',
            'edit posts',
            'delete posts',
            'create categories',
            'edit categories',
            'delete categories',
            'view component_section',
            'view ai_tools',
            'manage users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $superAdminName = config('filament-shield.super_admin.name', 'super_admin');
        Role::firstOrCreate(['name' => $superAdminName, 'guard_name' => 'web']);

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions($permissions);

        $clientRole = Role::firstOrCreate(['name' => 'client', 'guard_name' => 'web']);
        $clientRole->syncPermissions([
            'create posts',
            'edit posts',
            'delete posts',
            'create categories',
            'edit categories',
            'delete categories',
        ]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info('Roles created: ' . $superAdminName . ', admin, client');
    }
}