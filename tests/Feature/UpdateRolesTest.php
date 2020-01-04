<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UpdateRolesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_update_roles_and_change_permissions()
    {
        $this->signIn($user = create(User::class), 'admin');

        $role = Role::create(['name' => 'existing role']);
        $firstPermission = Permission::create(['name' => 'first permission']);
        $secondPermission = Permission::create(['name' => 'second permission']);
        $thirdPermission = Permission::create(['name' => 'third permission']);
        $role->givePermissionTo([$firstPermission, $secondPermission]);

        $this->withoutExceptionHandling()
            ->from('/roles')
            ->patch('/roles/' . $role->id, [
                'name' => 'existing role updated',
                'permissions' => [$firstPermission->id, $thirdPermission->id]
            ])->assertRedirect('/roles')
            ->assertSessionHasFlash('success', 'Role updated');

        tap($role->fresh(), function ($role) use (
                $firstPermission,
                $secondPermission,
                $thirdPermission
            ) {
            $this->assertEquals('existing role updated', $role->name);
            $this->assertTrue($role->hasPermissionTo($firstPermission));
            $this->assertFalse($role->hasPermissionTo($secondPermission));
            $this->assertTrue($role->hasPermissionTo($thirdPermission));
        });
    }
}
