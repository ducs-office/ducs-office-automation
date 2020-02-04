<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ViewRolesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_view_all_roles_and_all_permissions()
    {
        $this->signIn();

        $response = $this->get(route('staff.roles.index'))
            ->assertSuccessful()
            ->assertViewIs('staff.roles.index')
            ->assertViewHasAll(['roles', 'permissions']);

        $viewRoles = $response->viewData('roles');
        $viewPermissions = $response->viewData('permissions');

        $this->assertEquals(Role::count(), $viewRoles->count());
        $this->assertEquals(Permission::count(), $viewPermissions->flatten()->count());
    }
}
