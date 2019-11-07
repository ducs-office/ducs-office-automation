<?php

namespace Tests\Feature;

use App\Mail\UserRegisteredMail;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CreateNewUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function office_can_create_new_user_with_a_role()
    {
        Mail::fake();

        $teacherRole = Role::firstOrcreate(['name' => 'college teacher']);

        $this->signIn(create(User::class), 'office');

        $this->withoutExceptionHandling()
            ->post('/users', [
                'name' => $name = 'HOD Faculty',
                'email' => $email = 'hod@uni.ac.in',
                'roles' => [$teacherRole->id]
            ])->assertRedirect('/')
            ->assertSessionHasFlash('success', 'User created successfully!');

        $user = User::whereEmail($email)->first();

        $this->assertNotNull($user, 'User was not created.');
        $this->assertEquals($name, $user->name, 'User\'s name was not corretly set.');

        $this->assertTrue($user->hasRole($teacherRole), 'Created user was not assigned the expected role!');
    }

    /** @test */
    public function office_can_create_new_user_with_mutliple_roles()
    {
        Mail::fake();

        $facultyRole = Role::firstOrcreate(['name' => 'faculty']);
        $hodRole = Role::firstOrcreate(['name' => 'hod']);

        $this->signIn(create(User::class), 'office');

        $this->withoutExceptionHandling()
            ->post('/users', [
                'name' => $name = 'HOD Faculty',
                'email' => $email = 'hod@uni.ac.in',
                'roles' => [$facultyRole->id, $hodRole->id]
            ])->assertRedirect('/')
            ->assertSessionHasFlash('success', 'User created successfully!');

        $user = User::whereEmail($email)->first();
        $this->assertNotNull($user, 'User was not created.');
        $this->assertEquals($name, $user->name, 'User\'s name was not corretly set.');

        $this->assertTrue($user->hasRole([$facultyRole, $hodRole]), 'Created user was not assigned all roles!');
    }

    /** @test */
    public function credentials_are_sent_via_email_when_new_user_is_created()
    {
        Mail::fake();

        $this->signIn(create(User::class), 'office');

        $teacherRole = Role::firstOrCreate(['name' => 'college teacher']);

        $this->withoutExceptionHandling()
            ->post('/users', [
                'name' => 'college teacher',
                'email' => 'contact@teacher.me',
                'roles' => [$teacherRole->id]
            ])->assertRedirect('/')
            ->assertSessionHasFlash('success', 'User created successfully!');

        Mail::assertQueued(UserRegisteredMail::class, function ($mail) {
            return $mail->password && $mail->user;
        });
    }
}
