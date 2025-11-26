<?php

namespace Taba\Crm\Tests\Unit;

use Taba\Crm\Tests\TestCase;
use Taba\Crm\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserModelTest extends TestCase
{
    /** @test */
    public function it_can_create_a_user()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);
    }

    /** @test */
    public function it_hashes_password_on_creation()
    {
        $user = User::factory()->create([
            'password' => 'plain_password',
        ]);

        $this->assertTrue(Hash::check('plain_password', $user->password));
    }

    /** @test */
    public function it_can_assign_roles_to_user()
    {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'admin']);

        $user->assignRole('admin');

        $this->assertTrue($user->hasRole('admin'));
    }

    /** @test */
    public function it_can_create_super_admin_user()
    {
        $role = Role::create(['name' => 'super_admin']);

        $user = User::factory()->create([
            'email' => 'admin@example.com',
        ]);

        $user->assignRole('super_admin');

        $this->assertTrue($user->hasRole('super_admin'));
        $this->assertDatabaseHas('users', [
            'email' => 'admin@example.com',
        ]);
    }

    /** @test */
    public function it_uses_has_roles_trait()
    {
        $user = User::factory()->create();

        $this->assertTrue(method_exists($user, 'assignRole'));
        $this->assertTrue(method_exists($user, 'hasRole'));
        $this->assertTrue(method_exists($user, 'getRoleNames'));
    }
}
