<?php

namespace Tests\Feature;

use App\User;
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
            ->from('/users')
            ->patch('/users/' . $john->id, [
                'email' => $correctEmail = 'john@gmail.com'
            ])->assertRedirect('/users')
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
            ->from('/users')
            ->patch('/users/' . $john->id, [
                'name' => $correctName = 'John Doe'
            ])->assertRedirect('/users')
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
            ->from('/users')
            ->patch('/users/' . $john->id, [
                'roles' => [ $adminRole->id ]
            ])->assertRedirect('/users')
            ->assertSessionHasFlash('success', 'User updated successfully!');

        $this->assertTrue($john->fresh()->getRoleNames()->contains($adminRole->name));
        $this->assertFalse($john->fresh()->getRoleNames()->contains($facultyRole->name));
    }

    /** @test */
    public function admin_can_update_users_category()
    {
        $facultyRole = Role::firstOrCreate(['name' => 'faculty']);
        $john = create(User::class, 1, ['category' => 'College Teacher']);
        $john->assignRole($facultyRole);

        $this->signIn(create(User::class), 'admin');

        $this->withoutExceptionHandling()
            ->from('/users')
            ->patch('/users/' . $john->id, [
                'category' => $correctCategory = 'Faculty Teacher'
            ])->assertRedirect('/users')
            ->assertSessionHasFlash('success', 'User updated successfully!');

        tap($john->fresh(), function ($updated) use ($john, $correctCategory) {
            $this->assertEquals($correctCategory, $updated->category);
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
            ->from('/users')
            ->patch('/users/'.$user->id, [
            'email' => $user->email,
            'name' => $newName = 'New name',
            'category' => $newCategory = 'HOD'
        ])->assertRedirect('/users')
        ->assertSessionHasNoErrors()
        ->assertSessionHasFlash('success', 'User updated successfully!');


        $this->assertEquals(2, User::count());
        $this->assertEquals($newName, $user->fresh()->name);
        $this->assertEquals($newCategory, $user->fresh()->category);
    }
}
