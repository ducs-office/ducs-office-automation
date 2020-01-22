<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\CollegeTeacher;
use Illuminate\Validation\ValidationException;

class EditCollegeTeacherTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function email_of_college_teacher_can_be_edited()
    {
        $this->signIn();

        $email = 'abc@xyz.com';
        $college_teacher = create(CollegeTeacher::class, 1, ['email' => $email]);
    
        $this->withoutExceptionHandling()
            ->from('/college-teachers')
            ->patch("/college-teachers/$college_teacher->id", [
                'email' => $newEmail = 'pqrs@wxy.com'
            ])
            ->assertRedirect('/college-teachers')
            ->assertSessionHasFlash('success', 'College teacher updated successfully');

        $this->assertEquals($newEmail, $college_teacher->fresh()->email);
    }

    /** @test */
    public function email_of_college_teacher_should_be_unique()
    {
        $this->signIn();

        $email1 = 'email1@one.com';
        $college_teacher1 = create(CollegeTeacher::class, 1, ['email' => $email1]);
        $email2 = 'email2@two.com';
        $college_teacher2 = create(CollegeTeacher::class, 1, ['email' => $email2]);
    
        try {
            $this->withoutExceptionHandling()
                ->patch("/college-teachers/$college_teacher2->id", [
                    'email' => $email1
                ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('email', $e->errors());
        }

        $this->assertEquals($email2, $college_teacher2->fresh()->email);
    }

    /** @test */
    public function first_name_of_college_teacher_can_be_edited()
    {
        $this->signIn();

        $firstName = 'Bob';
        $college_teacher = create(CollegeTeacher::class, 1, ['first_name' => $firstName]);
    
        $this->withoutExceptionHandling()
            ->from('/college-teachers')
            ->patch("/college-teachers/$college_teacher->id", [
                'first_name' => $newFirstName = 'Joe'
            ])
            ->assertRedirect('/college-teachers')
            ->assertSessionHasFlash('success', 'College teacher updated successfully');

        $this->assertEquals($newFirstName, $college_teacher->fresh()->first_name);
    }

    /** @test */
    public function last_name_of_college_teacher_can_be_edited()
    {
        $this->signIn();

        $lastName = 'abc';
        $college_teacher = create(CollegeTeacher::class, 1, ['last_name' => $lastName]);
    
        $this->withoutExceptionHandling()
            ->from('/college-teachers')
            ->patch("/college-teachers/$college_teacher->id", [
                'last_name' => $newLastName = 'xyz'
            ])
            ->assertRedirect('/college-teachers')
            ->assertSessionHasFlash('success', 'College teacher updated successfully');

        $this->assertEquals($newLastName, $college_teacher->fresh()->last_name);
    }

    /** @test */
    public function college_teacher_is_not_validated_for_uniqueness_if_email_is_not_changed()
    {
        $this->signIn();

        $firstName = 'Bob';
        $college_teacher = create(CollegeTeacher::class, 1, ['first_name' => $firstName]);
    
        $this->withoutExceptionHandling()
            ->from('/college-teachers')
            ->patch("/college-teachers/$college_teacher->id", [
                'first_name' => $newFirstName = 'Joe',
                'last_name' => $college_teacher->last_name,
                'email' => $college_teacher->email
            ])
            ->assertRedirect('/college-teachers')
            ->assertSessionHasFlash('success', 'College teacher updated successfully');

        $this->assertEquals($newFirstName, $college_teacher->fresh()->first_name);
        $this->assertEquals(1, CollegeTeacher::count());
    }
}
