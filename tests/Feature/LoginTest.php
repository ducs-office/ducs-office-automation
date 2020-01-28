<?php

namespace Tests\Feature;

use App\Teacher;
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
    public function admin_can_login_with_correct_credentials()
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        $adminStaff = create(User::class, 1, [
            'password' => bcrypt($password = 'secret')
        ])->assignRole($adminRole);

        $this->withoutExceptionHandling();

        $this->post('/login', [
            'email' => $adminStaff->email,
            'password' => $password,
            'type' => 'web'
        ])->assertRedirect('/');

        $this->assertTrue(Auth::guard('web')->check(), 'User was expected to login but was not.');
    }

    /** @test */
    public function teachers_can_login()
    {
        $teacher = create(Teacher::class, 1, [
            'password' => bcrypt($plainPassword = 'secret')
        ]);

        $this->withoutExceptionHandling()->post('/login', [
            'email' => $teacher->email,
            'password' => $plainPassword,
            'type' => 'teachers'
        ])->assertRedirect('/teachers/profile');

        $this->assertTrue(Auth::guard('teachers')->check());
    }

    /** @test */
    public function teachers_cannot_login_on_invalid_guard()
    {
        $teacher = create(Teacher::class, 1, [
            'password' => bcrypt($plainPassword = 'secret')
        ]);

        $this->withExceptionHandling()
            ->from('/login')
            ->post('/login', [
                'email' => $teacher->email,
                'password' => $plainPassword,
                'type' => 'dsadasfm' // random
            ])->assertRedirect('/login')
            ->assertSessionHasErrors('type');

        $this->assertFalse(Auth::guard('teachers')->check());
    }

    /** @test */
    public function if_user_is_logged_in_redirect_to_home()
    {
        $this->signIn();
        $this->withoutExceptionHandling();
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

    /** @test */
    public function logged_in_teacher_can_logout()
    {
        $this->signInTeacher();

        $this->withoutExceptionHandling()
            ->post('/logout', ['type' => 'teachers'])
            ->assertRedirect('/');

        $this->assertFalse(Auth::guard('teachers')->check(), 'User was expected to be logged out, but was not logged out!');
    }
}
