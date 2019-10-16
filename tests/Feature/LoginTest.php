<?php

namespace Tests\Feature;

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
    public function admin_staff_can_login_with_correct_credentials()
    {
        $adminRole = Role::create(['name' => 'admin-staff']);

        $adminStaff = factory(\App\User::class)->create([
            'password' => bcrypt('secret')
        ])->assignRole($adminRole);

        $this->withoutExceptionHandling();

        $this->post('/login', [
            'email' => $adminStaff->email,
            'password' => 'secret'
        ])->assertRedirect('/');

        $this->assertTrue(Auth::check(), 'User was expected to login but was not.');
    }

    /** @test */
    public function external_teacher_can_login_with_correct_credentials()
    {
        $teacherRole = Role::create(['name' => 'teacher']);

        $teacher = factory(\App\User::class)->create([
            'password' => bcrypt('secret')
        ])->assignRole($teacherRole);

        $this->withoutExceptionHandling();

        $this->post('/login', [
            'email' => $teacher->email,
            'password' => 'secret'
        ])->assertRedirect('teacher/dashboard');

        $this->assertTrue(Auth::check(), 'User was expected to login but was not.');
    }

    /** @test */
    public function if_user_is_logged_in_redirect_to_home()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);

        $this->get('/login')->assertRedirect('/');
        $this->post('/login')->assertRedirect('/');
    }


    /** @test */
    public function logged_in_user_can_logout()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user)->withoutExceptionHandling();

        $this->post('/logout')->assertRedirect('/');

        $this->assertFalse(auth()->check(), 'User was expected to be logged out, but was not logged out!');
    }
}
