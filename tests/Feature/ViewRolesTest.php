<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
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

        $response = $this->withoutExceptionHandling()->get(route('staff.roles.index'))
            ->assertSuccessful()
            ->assertViewIs('staff.roles.index')
            ->assertViewHasAll(['roles']);

        $viewRoles = $response->viewData('roles');

        $this->assertEquals(Role::count(), $viewRoles->count());
        $this->assertArrayHasKey('permissions', $viewRoles->first()->toArray());
    }
}
