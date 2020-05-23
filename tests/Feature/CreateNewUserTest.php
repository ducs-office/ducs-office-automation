<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\UserRegisteredNotification;
use App\Types\Designation;
use App\Types\UserCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CreateNewUserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        Notification::fake();
    }

    private function fillCreateUserForm($overrides = [])
    {
        return $this->mergeFormFields([
            'first_name' => 'Naveen',
            'last_name' => 'Kumar',
            'category' => UserCategory::FACULTY_TEACHER,
            'email' => 'naveen.k@uni.ac.in',
            'roles' => function () {
                return [Role::firstOrCreate(['name' => $this->faker->word])->id];
            },
        ], $overrides);
    }

    /** @test */
    public function stores_new_faculty_teacher_user_with_atleast_one_role_as_supervisor_and_registered_notification_is_sent()
    {
        $this->signIn(create(User::class), 'admin');

        $createUserParams = $this->fillCreateUserForm([
            'category' => UserCategory::FACULTY_TEACHER,
            'is_supervisor' => true,
        ]);

        $this->withoutExceptionHandling()
            ->post(route('staff.users.store'), $createUserParams)
            ->assertRedirect('/')
            ->assertSessionHasFlash('success', 'User created successfully!');

        $user = User::query()
            ->where('first_name', $createUserParams['first_name'])
            ->where('last_name', $createUserParams['last_name'])
            ->where('email', $createUserParams['email'])
            ->where('category', UserCategory::FACULTY_TEACHER)
            ->get();

        $this->assertCount(1, $user, 'User was not created.');
        $this->assertTrue($user->first()->hasRole($createUserParams['roles'][0]), 'Created user was not assigned the expected role!');
        $this->assertTrue($user->first()->isSupervisor(), 'Created user was not a supervisor!');

        Notification::assertSentTo($user->first(), UserRegisteredNotification::class);
    }

    /** @test */
    public function stores_new_faculty_teacher_user_with_atleast_one_role_as_cosupervisor()
    {
        $this->signIn(create(User::class), 'admin');

        $createUserParams = $this->fillCreateUserForm([
            'category' => UserCategory::FACULTY_TEACHER,
            'is_cosupervisor' => true,
        ]);

        $this->withoutExceptionHandling()
            ->post(route('staff.users.store'), $createUserParams)
            ->assertRedirect('/')
            ->assertSessionHasFlash('success', 'User created successfully!');

        $user = User::query()
            ->where('email', $createUserParams['email'])
            ->where('category', UserCategory::FACULTY_TEACHER)
            ->get();

        $this->assertCount(1, $user, 'User was not created.');
        $this->assertTrue($user->first()->hasRole($createUserParams['roles'][0]), 'Created user was not assigned the expected role!');
        $this->assertTrue($user->first()->isCosupervisor(), 'Created user was not a supervisor!');
        $this->assertFalse($user->first()->isSupervisor(), 'Created user was also a supervisor, was\'nt expected!');
    }

    /** @test */
    public function required_fields_must_not_be_null_or_empty()
    {
        $this->signIn();

        try {
            $this->withoutExceptionHandling()
                ->post(route('staff.users.store'), $this->fillCreateUserForm([
                    'first_name' => '',
                    'last_name' => '',
                    'category' => '',
                    'email' => '',
                    'roles' => [],
                ]));
            $this->fail('Validation exception was expected. Empty fields were accepted.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('first_name', $e->errors());
            $this->assertArrayHasKey('last_name', $e->errors());
            $this->assertArrayHasKey('category', $e->errors());
            $this->assertArrayHasKey('email', $e->errors());
            $this->assertArrayHasKey('roles', $e->errors());
        }
        $this->assertEquals(1, User::count());
    }

    /** @test */
    public function request_validates_email_field_is_unique_value()
    {
        $this->signIn();
        $user = create(User::class);

        try {
            $this->withoutExceptionHandling()
                ->post(route('staff.users.store'), $this->fillCreateUserForm([
                    'email' => $user->email,
                ]));
            $this->fail('Validation exception was expected. Duplicate email was accepted.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('email', $e->errors());
        }
        $this->assertEquals(2, User::count());
    }

    /** @test */
    public function category_must_be_one_of_valid_values()
    {
        $this->signIn();

        try {
            $this->withoutExceptionHandling()
                ->post(route('staff.users.store'), $this->fillCreateUserForm([
                    'category' => 'some random category which is invalid',
                ]));
            $this->fail('Validation exception was expected. Invalid category was accepted.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('category', $e->errors());
        }
        $this->assertEquals(1, User::count());
    }

    /** @test */
    public function designation_affiliation_address_can_be_added_for_user_in_external_category()
    {
        $this->signIn();

        $createUserParams = $this->fillCreateUserForm([
            'category' => UserCategory::EXTERNAL,
            'designation' => $designation = $this->faker->words(3, true),
            'affiliation' => $affiliation = $this->faker->sentence(),
            'address' => $address = $this->faker->address,
        ]);

        $this->withoutExceptionHandling()
            ->post(route('staff.users.store'), $createUserParams);

        $user = User::query()
                ->where('email', $createUserParams['email'])
                ->where('category', UserCategory::EXTERNAL)
                ->where('designation', $designation)
                ->where('affiliation', $affiliation)
                ->where('address', $address)
                ->get();

        $this->assertCount(1, $user);
        $this->assertCount(0, $user->first()->roles);
    }

    /** @test */
    public function designation_affiliation_address_cannot_be_added_for_a_college_teacher_category()
    {
        $this->signIn();

        $createUserParams = $this->fillCreateUserForm([
            'category' => UserCategory::COLLEGE_TEACHER,
            'designation' => $designation = 'this should be ignored',
            'affiliation' => $affiliation = 'this should be ignored',
            'address' => $address = 'this should be ignored',
        ]);

        $this->withoutExceptionHandling()
            ->post(route('staff.users.store'), $createUserParams);

        $faculty_teacher = User::query()
                ->where('email', $createUserParams['email'])
                ->where('category', UserCategory::COLLEGE_TEACHER)
                ->get();

        $this->assertCount(1, $faculty_teacher);
        $this->assertNotEquals($designation, $faculty_teacher->first()->designation);
        $this->assertNotEquals($affiliation, $faculty_teacher->first()->affiliation);
        $this->assertNotEquals($address, $faculty_teacher->first()->address);
    }
}
