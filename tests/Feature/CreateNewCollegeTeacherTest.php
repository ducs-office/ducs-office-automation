<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use App\CollegeTeacher;
use App\Mail\UserRegisteredMail;

class CreateCollegeTeacherTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_not_store_a_college_teacher()
    {
        $this->withExceptionHandling()
            ->post('/college-teachers', [
                'first_name' => 'Sharanjit',
                'last_name' =>  'Kaur',
                'email' =>  'kaur.sharanjit@andc.du.ac.in'
            ])
            ->assertRedirect('/login');

        $this->assertEquals(0, CollegeTeacher::count());
    }

    /** @test */
    public function new_college_teacher_can_be_created()
    {
        $this->signIn();

        $this->withoutExceptionHandling()
            ->post('/college-teachers', [
                'first_name' => $firstName = 'Sharanjit',
                'last_name' => $lastName = 'Kaur',
                'email' => $email = 'kaur.sharanjit@andc.du.ac.in'
            ])
            ->assertRedirect('/college-teachers')
            ->assertSessionHasFlash('success', 'College Teacher created successfully!');

        $collegeTeacher = CollegeTeacher::first();

        $this->assertEquals($firstName, $collegeTeacher->first_name);
        $this->assertEquals($lastName, $collegeTeacher->last_name);
        $this->assertEquals($email, $collegeTeacher->email);
        
        $this->assertEquals(1, CollegeTeacher::count());
    }

    /** @test */
    public function credentials_are_sent_via_email_when_a_college_teacher_is_created()
    {
        Mail::fake();

        $this->signIn();

        $this->withoutExceptionHandling()
            ->post('/college-teachers', [
                'first_name' => 'Sharanjit',
                'last_name' => 'Kaur',
                'email' => 'kaur.sharanjit@andc.du.ac.in'
            ])
            ->assertRedirect('/college-teachers')
            ->assertSessionHasFlash('success', 'College Teacher created successfully!');
            
        $collegeTeacher = CollegeTeacher::first();

        Mail::assertQueued(UserRegisteredMail::class, function ($mail) use ($collegeTeacher) {
            return $mail->user && $mail->user->id == $collegeTeacher->id && $mail->password;
        });
    }

    /** @test */
    public function request_validates_first_name_is_not_null()
    {
        $this->signIn();

        try {
            $this->withoutExceptionHandling()
                ->post('/college-teachers', [
                    'first_name' => '',
                    'last_name' => 'Kaur',
                    'email' => 'kaur.sharanjit@andc.du.ac.in'
                ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('first_name', $e->errors());
        }

        $this->assertEquals(0, CollegeTeacher::count());
    }

    /** @test */
    public function request_validates_last_name_is_not_null()
    {
        $this->signIn();

        try {
            $this->withoutExceptionHandling()
                ->post('/college-teachers', [
                    'first_name' => 'Sharanjit',
                    'last_name' => '',
                    'email' => 'kaur.sharanjit@andc.du.ac.in'
                ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('last_name', $e->errors());
        }

        $this->assertEquals(0, CollegeTeacher::count());
    }

    /** @test */
    public function request_validates_email_is_not_null()
    {
        $this->signIn();

        try {
            $this->withoutExceptionHandling()
                ->post('/college-teachers', [
                    'first_name' => 'Sharanjit',
                    'last_name' => 'Kaur',
                    'email' => ''
                ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('email', $e->errors());
        }

        $this->assertEquals(0, CollegeTeacher::count());
    }

    /** @test */
    public function request_validates_email_is_unique()
    {
        $this->signIn();

        $collegeTeacher = create(CollegeTeacher::class);

        try {
            $this->withoutExceptionHandling()
                ->post('/college-teachers', [
                    'first_name' => 'Sharanjit',
                    'last_name' => 'Kaur',
                    'email' => $collegeTeacher->email
                ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('email', $e->errors());
        }

        $this->assertEquals(1, CollegeTeacher::count());
    }
}
