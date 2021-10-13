<?php

namespace Tests\Feature;

use App\Models\Scholar;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_form_test()
    {
        $response = $this->withoutExceptionHandling()->get(route('login-form'));
        $response->assertStatus(200);
    }

    /** @test */
    /*public function admin_can_login_with_correct_credentials()
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        $adminStaff = create(User::class, 1, [
            'password' => bcrypt($password = 'secret'),
        ])->assignRole($adminRole);

        $this->withoutExceptionHandling();

        $this->post(route('login'), [
            'email' => $adminStaff->email,
            'password' => $password,
        ])->assertRedirect();

        $this->assertTrue(Auth::guard('web')->check(), 'User was expected to login but was not.');
    }*/

    /** @test */
    public function if_user_is_logged_in_redirect_to_home()
    {
        $this->signIn();
        $this->withoutExceptionHandling();
        $this->get(route('login-form'))->assertRedirect();
        $this->post(route('login'))->assertRedirect();
    }

    /** @test */
    public function logged_in_user_can_logout()
    {
        $this->signIn();

        $this->withoutExceptionHandling()
            ->post(route('logout'))
            ->assertRedirect(route('login-form'));

        $this->assertFalse(auth()->check(), 'User was expected to be logged out, but was not logged out!');
    }

    /** @test */
    /*public function scholar_can_login()
    {
        $scholar = create(Scholar::class, 1, [
            'email' => $email = 'scholar@du.ac.in',
            'password' => bcrypt($plainPassword = 'secret'),
        ]);

        $this->withoutExceptionHandling()
            ->post(route('login', ['scholar']), [
                'email' => $email,
                'password' => $plainPassword,
            ])->assertRedirect();

        $this->assertTrue(Auth::guard('scholars')->check());
    }*/

    /** @test */
    public function logged_in_scholar_can_logout()
    {
        $this->signInScholar();

        $this->withoutExceptionHandling()
            ->post(route('logout', ['scholar']))
            ->assertRedirect();

        $this->assertFalse(Auth::guard('scholars')->check(), 'Scholar was expected to logout, but was not logged out!');
    }

    /** @test */
    /*public function scholars_cannot_login_from_regular_login()
    {
        $scholar = create(Scholar::class, 1, [
            'password' => bcrypt($plainPassword = 'secret'),
        ]);

        $this->withExceptionHandling()
            ->post(route('login'), [
                'email' => $scholar->email,
                'password' => $plainPassword,
            ])->assertRedirect()
            ->assertSessionHasErrors('email');

        $this->assertFalse(Auth::guard('scholars')->check());
    }*/

    /** @test */
    /*public function users_cannot_login_from_scholar_login()
    {
        $user = create(User::class, 1, [
            'password' => bcrypt($plainPassword = 'secret'),
        ]);

        $this->withExceptionHandling()
            ->post(route('login', ['scholar']), [
                'email' => $user->email,
                'password' => $plainPassword,
            ])->assertRedirect()
            ->assertSessionHasErrors('email');

        $this->assertFalse(Auth::guard('scholars')->check());
    }*/
}
