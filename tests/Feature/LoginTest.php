<?php

namespace Tests\Feature;

use App\Scholar;
use App\Teacher;
use App\User;
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
        $response = $this->withoutExceptionHandling()->get(route('login_form'));
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_login_with_correct_credentials()
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        $adminStaff = create(User::class, 1, [
            'password' => bcrypt($password = 'secret'),
        ])->assignRole($adminRole);

        $this->withoutExceptionHandling();

        $this->post(route('login'), [
            'email' => $adminStaff->email,
            'password' => $password,
            'type' => 'web',
        ])->assertRedirect();

        $this->assertTrue(Auth::guard('web')->check(), 'User was expected to login but was not.');
    }

    /** @test */
    public function teachers_can_login()
    {
        $teacher = create(Teacher::class, 1, [
            'password' => bcrypt($plainPassword = 'secret'),
        ]);

        $this->withoutExceptionHandling()->post(route('login'), [
            'email' => $teacher->email,
            'password' => $plainPassword,
            'type' => 'teachers',
        ])->assertRedirect();

        $this->assertTrue(Auth::guard('teachers')->check());
    }

    /** @test */
    public function teachers_cannot_login_on_invalid_guard()
    {
        $teacher = create(Teacher::class, 1, [
            'password' => bcrypt($plainPassword = 'secret'),
        ]);

        $this->withExceptionHandling()

            ->post(route('login'), [
                'email' => $teacher->email,
                'password' => $plainPassword,
                'type' => 'dsadasfm', // random
            ])->assertRedirect()
            ->assertSessionHasErrors('type');

        $this->assertFalse(Auth::guard('teachers')->check());
    }

    /** @test */
    public function if_user_is_logged_in_redirect_to_home()
    {
        $this->signIn();
        $this->withoutExceptionHandling();
        $this->get(route('login_form'))->assertRedirect();
        $this->post(route('login'))->assertRedirect();
    }

    /** @test */
    public function logged_in_user_can_logout()
    {
        $this->signIn();

        $this->withoutExceptionHandling()
            ->post(route('logout'))
            ->assertRedirect(route('login_form'));

        $this->assertFalse(auth()->check(), 'User was expected to be logged out, but was not logged out!');
    }

    /** @test */
    public function logged_in_teacher_can_logout()
    {
        $this->signInTeacher();

        $this->withoutExceptionHandling()
            ->post(route('logout'), ['type' => 'teachers'])
            ->assertRedirect();

        $this->assertFalse(Auth::guard('teachers')->check(), 'User was expected to be logged out, but was not logged out!');
    }

    /** @test */
    public function scholar_can_login()
    {
        $scholar = create(Scholar::class, 1, [
            'email' => $email = 'scholar@du.ac.in',
            'password' => bcrypt($plainPassword = 'secret'),
        ]);

        $this->withoutExceptionHandling()
            ->post(route('login'), [
                'email' => $email,
                'password' => $plainPassword,
                'type' => 'scholars',
            ])->assertRedirect();

        $this->assertTrue(Auth::guard('scholars')->check());
    }

    /** @test */
    public function logged_in_scholar_can_logout()
    {
        $this->signInScholar();

        $this->withoutExceptionHandling()
            ->post(route('logout'), ['type' => 'scholars'])
            ->assertRedirect();

        $this->assertFalse(Auth::guard('scholars')->check(), 'Scholar was expected to logout, but was not logged out!');
    }

    /** @test */
    public function scholars_cannot_login_on_invalid_guard()
    {
        $scholar = create(Scholar::class, 1, [
            'password' => bcrypt($plainPassword = 'secret'),
        ]);

        $this->withExceptionHandling()
            ->post(route('login'), [
                'email' => $scholar->email,
                'password' => $plainPassword,
                'type' => 'tyuhgyt',
            ])->assertRedirect()
            ->assertSessionHasErrors('type');

        $this->assertFalse(Auth::guard('scholars')->check());
    }
}
