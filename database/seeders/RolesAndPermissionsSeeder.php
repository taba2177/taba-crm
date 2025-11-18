<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all permissions
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

        // Create permissions only if they don't exist
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles only if they don't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions([
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
        ]);

        $clientRole = Role::firstOrCreate(['name' => 'client', 'guard_name' => 'web']);
        $clientRole->syncPermissions([
            'create posts',
            'edit posts',
            'delete posts',
            'create categories',
            'edit categories',
            'delete categories',
        ]);

        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $superAdminRole->syncPermissions(Permission::all());
    }
}
