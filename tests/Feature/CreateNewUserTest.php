<?php

namespace Tests\Feature;

use App\Mail\UserRegisteredMail;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CreateNewUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_create_new_user_with_a_role()
    {
        Mail::fake();

        $teacherRole = Role::firstOrcreate(['name' => 'college teacher']);

        $this->signIn(create(User::class), 'admin');

        $this->withoutExceptionHandling()
            ->post('/users', [
                'name' => $name = 'HOD Faculty',
                'email' => $email = 'hod@uni.ac.in',
                'category' => 'hod',
                'roles' => [$teacherRole->id]
            ])->assertRedirect('/')
            ->assertSessionHasFlash('success', 'User created successfully!');

        $user = User::whereEmail($email)->first();

        $this->assertNotNull($user, 'User was not created.');
        $this->assertEquals($name, $user->name, 'User\'s name was not corretly set.');

        $this->assertTrue($user->hasRole($teacherRole), 'Created user was not assigned the expected role!');
    }

    /** @test */
    public function admin_can_create_new_user_with_mutliple_roles()
    {
        Mail::fake();

        $facultyRole = Role::firstOrcreate(['name' => 'faculty']);
        $hodRole = Role::firstOrcreate(['name' => 'hod']);

        $this->signIn(create(User::class), 'admin');

        $this->withoutExceptionHandling()
            ->post('/users', [
                'name' => $name = 'HOD Faculty',
                'email' => $email = 'hod@uni.ac.in',
                'category' => 'hod',
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

        $this->signIn(create(User::class), 'admin');

        $teacherRole = Role::firstOrCreate(['name' => 'college teacher']);

        $this->withoutExceptionHandling()
            ->post('/users', [
                'name' => 'college teacher',
                'email' => 'contact@teacher.me',
                'category' => 'college_teacher',
                'roles' => [$teacherRole->id]
            ])->assertRedirect('/')
            ->assertSessionHasFlash('success', 'User created successfully!');

        Mail::assertQueued(UserRegisteredMail::class, function ($mail) {
            return $mail->password && $mail->user;
        });
    }

    /** @test */
    public function request_validates_name_field_is_not_null()
    {
        $this->signIn();

        $teacherRole = Role::firstOrcreate(['name' => 'college teacher']);

        try {
            $this->withoutExceptionHandling()
                ->post('/users', [
                    'name' => '',
                    'email' => 'hod@uni.ac.in',
                    'category' => 'hod',
                    'roles' => [$teacherRole->id]
                ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('name', $e->errors());
        }
        $this->assertEquals(1, User::count());
    }

    /** @test */
    public function request_validates_email_field_is_not_null()
    {
        $this->signIn();

        $teacherRole = Role::firstOrcreate(['name' => 'college teacher']);

        try {
            $this->withoutExceptionHandling()
                ->post('/users', [
                    'name' => 'HOD Faculty',
                    'email' => '',
                    'category' => 'hod',
                    'roles' => [$teacherRole->id]
                ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('email', $e->errors());
        }
        $this->assertEquals(1, User::count());
    }

    /** @test */
    public function request_validates_email_field_is_unique_value()
    {
        $this->signIn();

        $user = create(User::class);
        $teacherRole = Role::firstOrcreate(['name' => 'college teacher']);

        try {
            $this->withoutExceptionHandling()
                ->post('/users', [
                    'name' => 'HOD Faculty',
                    'email' => $user->email,
                    'category' => 'hod',
                    'roles' => [$teacherRole->id]
                ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('email', $e->errors());
        }
        $this->assertEquals(2, User::count());
    }

    /** @test */
    public function request_validates_category_field_is_not_null()
    {
        $this->signIn();

        $teacherRole = Role::firstOrcreate(['name' => 'college teacher']);

        try {
            $this->withoutExceptionHandling()
                ->post('/users', [
                    'name' => 'HOD Faculty',
                    'email' => 'hod@uni.ac.in',
                    'category' => '',
                    'roles' => [$teacherRole->id]
                ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('category', $e->errors());
        }
        $this->assertEquals(1, User::count());
    }

    /** @test */
    public function request_validates_category_field_is_a_valid_value()
    {
        $this->signIn();

        $teacherRole = Role::firstOrcreate(['name' => 'college teacher']);

        try {
            $this->withoutExceptionHandling()
                ->post('/users', [
                    'name' => 'HOD Faculty',
                    'email' => 'hod@uni.ac.in',
                    'category' => 'InvalidCategory123',
                    'roles' => [$teacherRole->id]
                ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('category', $e->errors());
        }
        $this->assertEquals(1, User::count());
    }

    /** @test */
    public function request_validates_roles_field_is_not_null()
    {
        $this->signIn();

        $teacherRole = Role::firstOrcreate(['name' => 'college teacher']);

        try {
            $this->withoutExceptionHandling()
                ->post('/users', [
                    'name' => 'HOD Faculty',
                    'email' => 'hod@uni.ac.in',
                    'category' => 'hod',
                    'roles' => ''
                ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('roles', $e->errors());
        }
        $this->assertEquals(1, User::count());
    }
}
