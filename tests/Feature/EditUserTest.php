<?php

namespace Tests\Feature;

use App\Models\User;
use App\Types\UserCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class EditUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_update_users_email()
    {
        $facultyRole = Role::firstOrCreate(['name' => 'faculty']);
        $john = create(User::class, 1, ['email' => 'john.errored@gmail.com']);
        $john->assignRole($facultyRole);

        $this->signIn(create(User::class), 'admin');

        $this->withoutExceptionHandling()
            ->patch(route('staff.users.update', $john), [
                'email' => $correctEmail = 'john@gmail.com',
            ])->assertRedirect()
            ->assertSessionHasFlash('success', 'User updated successfully!');

        tap($john->fresh(), function ($updated) use ($john, $correctEmail) {
            $this->assertEquals($correctEmail, $updated->email);
            $this->assertEquals($john->name, $updated->name);
            $this->assertEquals($john->category, $updated->category);
        });

        $this->assertEquals($facultyRole->name, $john->getRoleNames()->first());
    }

    /** @test */
    public function admin_can_update_users_name()
    {
        $facultyRole = Role::firstOrCreate(['name' => 'faculty']);
        $john = create(User::class, 1, ['name' => 'John Foo']);
        $john->assignRole($facultyRole);

        $this->signIn(create(User::class), 'admin');

        $this->withoutExceptionHandling()
            ->patch(route('staff.users.update', $john), [
                'name' => $correctName = 'John Doe',
            ])->assertRedirect()
            ->assertSessionHasFlash('success', 'User updated successfully!');

        tap($john->fresh(), function ($updated) use ($john, $correctName) {
            $this->assertEquals($correctName, $updated->name);
            $this->assertEquals($john->email, $updated->email);
            $this->assertEquals($john->category, $updated->category);
        });

        $this->assertEquals($facultyRole->name, $john->getRoleNames()->first());
    }

    /** @test */
    public function admin_can_update_users_roles()
    {
        $facultyRole = Role::firstOrCreate(['name' => 'faculty']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        $john = create(User::class, 1, ['name' => 'John Foo']);
        $john->assignRole($facultyRole);

        $this->signIn(create(User::class), 'admin');

        $this->withoutExceptionHandling()
            ->patch(route('staff.users.update', $john), [
                'roles' => [$adminRole->id],
            ])->assertRedirect()
            ->assertSessionHasFlash('success', 'User updated successfully!');

        $this->assertTrue($john->fresh()->getRoleNames()->contains($adminRole->name));
        $this->assertFalse($john->fresh()->getRoleNames()->contains($facultyRole->name));
    }

    /** @test */
    public function admin_can_update_users_category()
    {
        $allTypes = UserCategory::values();

        $facultyRole = Role::firstOrCreate(['name' => 'faculty']);
        $john = create(User::class, 1, ['category' => $allTypes[0]]);
        $john->assignRole($facultyRole);

        $this->signIn(create(User::class), 'admin');

        $this->withoutExceptionHandling()
            ->patch(route('staff.users.update', $john), [
                'category' => $newCategory = $allTypes[1],
            ])->assertRedirect()
            ->assertSessionHasFlash('success', 'User updated successfully!');

        tap($john->fresh(), function ($updated) use ($john, $newCategory) {
            $this->assertEquals($newCategory, (string) $updated->category);
            $this->assertEquals($john->email, $updated->email);
            $this->assertEquals($john->name, $updated->name);
        });

        $this->assertEquals($facultyRole->name, $john->getRoleNames()->first());
    }

    /** @test */
    public function user_is_not_validated_for_uniqueness_if_email_is_not_changed()
    {
        $this->signIn();

        $user = create(User::class);

        $this->withoutExceptionHandling()
            ->patch(route('staff.users.update', $user), [
                'email' => $user->email,
                'name' => $newName = 'New name',
                'category' => $newType = UserCategory::OFFICE_STAFF,
            ])->assertRedirect()
        ->assertSessionHasNoErrors()
        ->assertSessionHasFlash('success', 'User updated successfully!');

        $this->assertEquals(2, User::count());
        $this->assertEquals($newName, $user->fresh()->name);
        $this->assertEquals($newType, $user->fresh()->category);
    }

    /** @test */
    public function make_user_a_supervisor_test()
    {
        $this->signIn();

        $user = create(User::class);

        $this->withoutExceptionHandling()
            ->patch(route('staff.users.update', $user), [
                'is_supervisor' => true,
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'User updated successfully!');

        $this->assertTrue($user->fresh()->isSupervisor(), 'User was not made a supervisor.');
    }

    /** @test */
    public function only_college_teacher_faculty_teacher_can_be_made_a_supervisor()
    {
        //TODO: Fill this test
        $this->assertTrue(true);
    }

    /** @test */
    public function user_of_type_teacher_can_be_made_a_supervisor_only_if_their_profile_has_a_college_set()
    {
        $this->signIn();

        $teacher = create(User::class, 1, [
            'category' => UserCategory::COLLEGE_TEACHER,
            'college_id' => null,
        ]);

        $this->withoutExceptionHandling()
            ->patch(route('staff.users.update', $teacher), [
                'is_supervisor' => true,
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'User updated successfully');

        $this->assertTrue($teacher->fresh()->isSupervisor(), 'teacher wasn\'t made a supervisor');
    }
}
