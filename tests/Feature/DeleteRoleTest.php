<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DeleteRoleTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function office_staff_can_delete_existing_roles()
    {
        $role = Role::create(['name' => 'existing role']);

        $this->signIn(create(User::class), 'office');
        
        $this->withoutExceptionHandling()
            ->from('/roles')
            ->delete('/roles/' . $role->name)
            ->assertRedirect('/roles')
            ->assertSessionHasFlash('success', 'Role deleted');

        $this->assertNull($role->fresh());
    }
}
