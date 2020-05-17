<?php

namespace Tests\Feature;

use App\Mail\UserRegisteredMail;
use App\Models\User;
use App\Notifications\UserRegisteredNotification;
use App\Types\UserCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
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

        $teacherRole = Role::firstOrcreate(['name' => 'teacher']);

        $this->signIn(create(User::class), 'admin');

        $this->withoutExceptionHandling()
            ->post(route('staff.users.store'), [
                'name' => $name = 'Naveen Kumar',
                'email' => $email = 'naveen.k@uni.ac.in',
                'category' => UserCategory::FACULTY_TEACHER,
                'roles' => [$teacherRole->id],
            ])->assertRedirect('/')
            ->assertSessionHasFlash('success', 'User created successfully!');

        $user = User::whereEmail($email)->first();

        $this->assertNotNull($user, 'User was not created.');
        $this->assertEquals($name, $user->name, 'User\'s name was not corretly set.');

        $this->assertTrue($user->hasRole($teacherRole), 'Created user was not assigned the expected role!');
    }

    /** @test */
    public function admin_can_create_new_supervisor_user_with_supervisor_profile()
    {
        Mail::fake();

        $teacherRole = Role::firstOrcreate(['name' => 'teacher']);

        $this->signIn(create(User::class), 'admin');

        $this->withoutExceptionHandling()
            ->post(route('staff.users.store'), [
                'name' => $name = 'PK Hazra',
                'email' => $email = 'hazra.pk@uni.ac.in',
                'category' => UserCategory::FACULTY_TEACHER,
                'roles' => [$teacherRole->id],
                'is_supervisor' => true,
            ])->assertRedirect('/')
            ->assertSessionHasFlash('success', 'User created successfully!');

        $user = User::whereEmail($email)->first();

        $this->assertTrue($user->isSupervisor(), 'User\'s supervisor profile wasn\'t created');
    }

    /** @test */
    public function admin_can_create_new_user_with_mutliple_roles()
    {
        Mail::fake();

        $facultyRole = Role::firstOrcreate(['name' => 'faculty']);
        $hodRole = Role::firstOrcreate(['name' => 'hod']);

        $this->signIn(create(User::class), 'admin');

        $this->withoutExceptionHandling()
            ->post(route('staff.users.store'), [
                'name' => $name = 'Megha Khandelwal',
                'email' => $email = 'megha@uni.ac.in',
                'category' => UserCategory::FACULTY_TEACHER,
                'roles' => [$facultyRole->id, $hodRole->id],
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
        Notification::fake();

        $this->signIn(create(User::class), 'admin');

        $facultyTeacherRole = Role::firstOrCreate(['name' => 'faculty_teacher']);

        $this->withoutExceptionHandling()
            ->post(route('staff.users.store'), [
                'name' => 'Sapna Vaarshney',
                'email' => $email = 'sapnav@cs.du.ac.in',
                'category' => UserCategory::FACULTY_TEACHER,
                'roles' => [$facultyTeacherRole->id],
            ])->assertRedirect('/')
            ->assertSessionHasFlash('success', 'User created successfully!');

        $user = User::whereEmail($email)->first();
        $this->assertNotNull($user);

        Notification::assertSentTo($user, UserRegisteredNotification::class);
    }

    /** @test */
    public function request_validates_name_field_is_not_null()
    {
        $this->signIn();

        $teacherRole = Role::firstOrcreate(['name' => 'teacher']);

        try {
            $this->withoutExceptionHandling()
                ->post(route('staff.users.store'), [
                    'name' => '',
                    'email' => 'hod@uni.ac.in',
                    'category' => UserCategory::FACULTY_TEACHER,
                    'roles' => [$teacherRole->id],
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

        $teacherRole = Role::firstOrcreate(['name' => 'teacher']);

        try {
            $this->withoutExceptionHandling()
                ->post(route('staff.users.store'), [
                    'name' => 'HOD Faculty',
                    'email' => '',
                    'category' => UserCategory::FACULTY_TEACHER,
                    'roles' => [$teacherRole->id],
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
        $teacherRole = Role::firstOrcreate(['name' => 'teacher']);

        try {
            $this->withoutExceptionHandling()
                ->post(route('staff.users.store'), [
                    'name' => 'HOD Faculty',
                    'email' => $user->email,
                    'category' => UserCategory::FACULTY_TEACHER,
                    'roles' => [$teacherRole->id],
                ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('email', $e->errors());
        }
        $this->assertEquals(2, User::count());
    }

    /** @test */
    public function request_validates_type_field_is_not_null()
    {
        $this->signIn();

        $teacherRole = Role::firstOrcreate(['name' => 'teacher']);

        try {
            $this->withoutExceptionHandling()
                ->post(route('staff.users.store'), [
                    'name' => 'HOD Faculty',
                    'email' => 'hod@uni.ac.in',
                    'category' => '',
                    'roles' => [$teacherRole->id],
                ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('category', $e->errors());
        }
        $this->assertEquals(1, User::count());
    }

    /** @test */
    public function request_validates_type_field_is_a_valid_value()
    {
        $this->signIn();

        $teacherRole = Role::firstOrcreate(['name' => 'teacher']);

        try {
            $this->withoutExceptionHandling()
                ->post(route('staff.users.store'), [
                    'name' => 'HOD Faculty',
                    'email' => 'hod@uni.ac.in',
                    'category' => 'InvalidCategory123',
                    'roles' => [$teacherRole->id],
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

        $teacherRole = Role::firstOrcreate(['name' => 'teacher']);

        try {
            $this->withoutExceptionHandling()
                ->post(route('staff.users.store'), [
                    'name' => 'Faculty Teacher',
                    'email' => 'teacher@uni.ac.in',
                    'category' => UserCategory::FACULTY_TEACHER,
                    'roles' => '',
                ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('roles', $e->errors());
        }
        $this->assertEquals(1, User::count());
    }
}
