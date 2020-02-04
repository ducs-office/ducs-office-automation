<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Teacher;
use Illuminate\Validation\ValidationException;

class EditTeacherTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function email_of_teacher_can_be_edited()
    {
        $this->signIn();

        $email = 'abc@xyz.com';
        $teacher = create(Teacher::class, 1, ['email' => $email]);

        $this->withoutExceptionHandling()
            ->patch(route('staff.teachers.update', $teacher), [
                'email' => $newEmail = 'pqrs@wxy.com'
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'College teacher updated successfully');

        $this->assertEquals($newEmail, $teacher->fresh()->email);
    }

    /** @test */
    public function email_of_teacher_should_be_unique()
    {
        $this->signIn();

        $email1 = 'email1@one.com';
        $teacher1 = create(Teacher::class, 1, ['email' => $email1]);
        $email2 = 'email2@two.com';
        $teacher2 = create(Teacher::class, 1, ['email' => $email2]);

        try {
            $this->withoutExceptionHandling()
                ->patch(route('staff.teachers.update', $teacher1), [
                    'email' => $email1
                ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('email', $e->errors());
        }

        $this->assertEquals($email2, $teacher2->fresh()->email);
    }

    /** @test */
    public function first_name_of_teacher_can_be_edited()
    {
        $this->signIn();

        $firstName = 'Bob';
        $teacher = create(Teacher::class, 1, ['first_name' => $firstName]);

        $this->withoutExceptionHandling()
            ->patch(route('staff.teachers.update', $teacher), [
                'first_name' => $newFirstName = 'Joe'
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'College teacher updated successfully');

        $this->assertEquals($newFirstName, $teacher->fresh()->first_name);
    }

    /** @test */
    public function last_name_of_teacher_can_be_edited()
    {
        $this->signIn();

        $lastName = 'abc';
        $teacher = create(Teacher::class, 1, ['last_name' => $lastName]);

        $this->withoutExceptionHandling()
            ->patch(route('staff.teachers.update', $teacher), [
                'last_name' => $newLastName = 'xyz'
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'College teacher updated successfully');

        $this->assertEquals($newLastName, $teacher->fresh()->last_name);
    }

    /** @test */
    public function teacher_is_not_validated_for_uniqueness_if_email_is_not_changed()
    {
        $this->signIn();

        $firstName = 'Bob';
        $teacher = create(Teacher::class, 1, ['first_name' => $firstName]);

        $this->withoutExceptionHandling()
            ->patch(route('staff.teachers.update', $teacher), [
                'first_name' => $newFirstName = 'Joe',
                'last_name' => $teacher->last_name,
                'email' => $teacher->email
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'College teacher updated successfully');

        $this->assertEquals($newFirstName, $teacher->fresh()->first_name);
        $this->assertEquals(1, Teacher::count());
    }
}
