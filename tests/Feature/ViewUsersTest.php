<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ViewUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_view_all_users()
    {
        $users = create(User::class, 5);

        $this->signIn($users[0], 'admin');

        $response = $this->withoutExceptionHandling()
            ->get(route('staff.users.index'))
            ->assertSuccessful()
            ->assertViewIs('staff.users.index')
            ->assertViewHasAll(['users', 'roles', 'categories']);

        $this->assertCount(User::count(), $response->viewData('users'));
        $this->assertCount(Role::count(), $response->viewData('roles'));
    }
}
