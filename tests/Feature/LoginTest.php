<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_form_test()
    {
        $response = $this->withoutExceptionHandling()->get('/login');
        $response->assertStatus(200);
    }

    /** @test */
    public function office_can_login_with_correct_credentials()
    {
        $adminRole = Role::firstOrCreate(['name' => 'office']);

        $adminStaff = create(User::class, 1, [
            'password' => bcrypt($password = 'secret')
        ])->assignRole($adminRole);

        $this->withoutExceptionHandling();

        $this->post('/login', [
            'email' => $adminStaff->email,
            'password' => $password
        ])->assertRedirect('/');

        $this->assertTrue(Auth::check(), 'User was expected to login but was not.');
    }

    /** @test */
    public function if_user_is_logged_in_redirect_to_home()
    {
        $this->signIn();

        $this->get('/login')->assertRedirect('/');
        $this->post('/login')->assertRedirect('/');
    }


    /** @test */
    public function logged_in_user_can_logout()
    {
        $this->signIn();

        $this->withoutExceptionHandling()
            ->post('/logout')
            ->assertRedirect('/');

        $this->assertFalse(auth()->check(), 'User was expected to be logged out, but was not logged out!');
    }
}
