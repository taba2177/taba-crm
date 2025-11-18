<?php

namespace Taba\Crm\Tests\Feature;

use Taba\Crm\Tests\TestCase;
use Taba\Crm\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsTest extends TestCase
{
    /** @test */
    public function it_can_create_roles()
    {
        $superAdminRole = Role::create(['name' => 'super-admin']);
        $adminRole = Role::create(['name' => 'admin']);
        $clientRole = Role::create(['name' => 'client']);

        $this->assertDatabaseHas('roles', ['name' => 'super-admin']);
        $this->assertDatabaseHas('roles', ['name' => 'admin']);
        $this->assertDatabaseHas('roles', ['name' => 'client']);
    }

    /** @test */
    public function it_can_create_permissions()
    {
        $permission = Permission::create(['name' => 'view posts']);

        $this->assertDatabaseHas('permissions', ['name' => 'view posts']);
    }

    /** @test */
    public function it_can_assign_permissions_to_roles()
    {
        $role = Role::create(['name' => 'admin']);
        $permission = Permission::create(['name' => 'edit posts']);

        $role->givePermissionTo($permission);

        $this->assertTrue($role->hasPermissionTo('edit posts'));
    }

    /** @test */
    public function super_admin_user_has_all_permissions()
    {
        $role = Role::create(['name' => 'super-admin']);

        $permissions = [
            'view posts',
            'create posts',
            'edit posts',
            'delete posts',
        ];

        foreach ($permissions as $permissionName) {
            $permission = Permission::create(['name' => $permissionName]);
            $role->givePermissionTo($permission);
        }

        $user = User::factory()->create();
        $user->assignRole('super-admin');

        foreach ($permissions as $permissionName) {
            $this->assertTrue($user->hasPermissionTo($permissionName));
        }
    }

    /** @test */
    public function client_user_has_limited_permissions()
    {
        $adminRole = Role::create(['name' => 'admin']);
        $clientRole = Role::create(['name' => 'client']);

        $viewPermission = Permission::create(['name' => 'view posts']);
        $editPermission = Permission::create(['name' => 'edit posts']);

        $adminRole->givePermissionTo([$viewPermission, $editPermission]);
        $clientRole->givePermissionTo($viewPermission);

        $clientUser = User::factory()->create();
        $clientUser->assignRole('client');

        $this->assertTrue($clientUser->hasPermissionTo('view posts'));
        $this->assertFalse($clientUser->hasPermissionTo('edit posts'));
    }

    /** @test */
    public function it_can_check_multiple_roles()
    {
        Role::create(['name' => 'super-admin']);
        Role::create(['name' => 'admin']);

        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->assertTrue($user->hasRole('admin'));
        $this->assertFalse($user->hasRole('super-admin'));
    }

    /** @test */
    public function it_can_sync_roles()
    {
        $role1 = Role::create(['name' => 'admin']);
        $role2 = Role::create(['name' => 'client']);

        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->assertTrue($user->hasRole('admin'));

        $user->syncRoles(['client']);

        $this->assertFalse($user->hasRole('admin'));
        $this->assertTrue($user->hasRole('client'));
    }
}
