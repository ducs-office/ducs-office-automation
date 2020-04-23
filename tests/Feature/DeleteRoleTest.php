<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DeleteRoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_delete_existing_roles()
    {
        $role = Role::create(['name' => 'existing role']);

        $this->signIn(create(User::class), 'admin');

        $this->withoutExceptionHandling()

            ->delete(route('staff.roles.destroy', $role))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Role deleted');

        $this->assertNull($role->fresh());
    }

    /** @test */
    public function user_cannot_delete_thier_own_role()
    {
        $role = Role::create(['name' => 'my role']);

        $this->signIn(create(User::class), 'my role');

        $this->withExceptionHandling()

            ->delete(route('staff.roles.destroy', $role))
            ->assertForbidden();

        $this->assertNotNull($role->fresh());
    }
}
