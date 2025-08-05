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

        // create permissions
        // create permissions for articles (existing)
        Permission::create(['name' => 'edit articles']);
        Permission::create(['name' => 'delete articles']);
        Permission::create(['name' => 'publish articles']);
        Permission::create(['name' => 'unpublish articles']);

        // create permissions for posts
        Permission::create(['name' => 'create posts']);
        Permission::create(['name' => 'edit posts']);
        Permission::create(['name' => 'delete posts']);

        // create permissions for categories
        Permission::create(['name' => 'create categories']);
        Permission::create(['name' => 'edit categories']);
        Permission::create(['name' => 'delete categories']);

        // create permissions for specific features
        Permission::create(['name' => 'view component_section']);
        Permission::create(['name' => 'view ai_tools']);
        Permission::create(['name' => 'manage users']);

        // create roles and assign created permissions
        $adminRole = Role::create(['name' => 'admin'])
            ->givePermissionTo([
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

        $clientRole = Role::create(['name' => 'client'])
            ->givePermissionTo([
                'create posts',
                'edit posts',
                'delete posts',
                'create categories',
                'edit categories',
                'delete categories',
            ]);

        $superAdminRole = Role::create(['name' => 'super-admin']);
        $superAdminRole->givePermissionTo(Permission::all());
    }
}
