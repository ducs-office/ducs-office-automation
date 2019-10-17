<?php

namespace Tests\Feature;

use App\Mail\UserRegisteredMail;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CreateNewUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_staff_can_retrive_create_teacher_form()
    {
        $this->signIn(create(User::class), 'admin_staff');

        $viewRoles = $this->withoutExceptionHandling()
            ->get("/users/create")
            ->assertSuccessful()
            ->assertViewIs('users.create')
            ->assertViewHas('roles')
            ->viewData('roles');

        $this->assertCount(Role::count(), $viewRoles);
    }

    /** @test */
    public function admin_staff_can_create_new_teacher()
    {
        Mail::fake();

        $teacherRole = Role::firstOrcreate(['name' => 'teacher']);

        $this->signIn(create(User::class), 'admin_staff');

        $this->withoutExceptionHandling()
            ->post('/users', [
                'name' => 'Teacher',
                'email' => 'contact@teacher.me',
                'role_id' => $teacherRole->id
            ])->assertRedirect('/')
            ->assertSessionHasFlash('success', 'User created successfully!');

        tap(
            User::whereEmail('contact@teacher.me')->whereName('Teacher')->first(),
            function($user) use ($teacherRole) {
                $this->assertNotNull($user, 'User was not created.');
                $this->assertTrue($user->hasRole($teacherRole), 'Created user is not a teacher!');
            }
        );
    }

    /** @test */
    public function credentials_are_sent_via_email_when_new_user_is_created()
    {
        Mail::fake();

        $this->signIn(create(User::class), 'admin_staff');

        $teacherRole = Role::firstOrCreate(['name' => 'teacher']);

        $this->withoutExceptionHandling()
            ->post('/users', [
                'name' => 'Teacher',
                'email' => 'contact@teacher.me',
                'role_id' => $teacherRole->id
            ])->assertRedirect('/')
            ->assertSessionHasFlash('success', 'User created successfully!');

        Mail::assertQueued(UserRegisteredMail::class, function($mail) {
            return $mail->password;
        });
    }
}
