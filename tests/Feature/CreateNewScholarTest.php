<?php

namespace Tests\Feature;

use App\Mail\UserRegisteredMail;
use App\Scholar;
use App\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CreateNewScholarTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function scholars_can_not_be_created_by_a_guest()
    {
        $this->withExceptionHandling()
            ->post(route('staff.scholars.create'), [
                'first_name' => 'Pushkar',
                'last_name' => 'Sonkar',
                'email' => 'pushkar@cs.du.ac.in',
            ])->assertRedirect();

        $this->assertEquals(0, Scholar::count());
    }

    /** @test */
    public function scholar_can_be_created()
    {
        $this->signIn();

        $scholar = [
            'first_name' => 'Pushkar',
            'last_name' => 'Sonkar',
            'email' => 'pushkar@cs.du.ac.in',
        ];

        $this->withoutExceptionHandling()
            ->post(route('staff.scholars.create'), $scholar)
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'New scholar added succesfully!');

        $this->assertEquals(1, Scholar::count());

        $this->assertEquals($scholar['first_name'], Scholar::first()->first_name);
        $this->assertEquals($scholar['last_name'], Scholar::first()->last_name);
        $this->assertEquals($scholar['email'], Scholar::first()->email);
    }

    /** @test */
    public function credentials_are_sent_via_mail_to_a_registered_scholar()
    {
        Mail::fake();

        $this->signIn();

        $this->withoutExceptionHandling()
            ->post(route('staff.scholars.create'), [
                'first_name' => 'Pushkar',
                'last_name' => 'Sonkar',
                'email' => 'pushkar@cs.du.ac.in',
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'New scholar added succesfully!');

        $scholar = Scholar::first();

        Mail::assertQueued(UserRegisteredMail::class, function ($mail) use ($scholar) {
            $data = $mail->build()->viewData;
            $this->assertArrayHasKey('user', $data);
            $this->assertArrayHasKey('password', $data);
            return (int) $data['user']->id === (int) $scholar->id;
        });
    }

    /** @test */
    public function request_validates_first_name_can_not_be_null()
    {
        $this->signIn();

        try {
            $this->withoutExceptionHandling()
                ->post(route('staff.scholars.create'), [
                    'first_name' => '',
                    'last_name' => 'Sonkar',
                    'email' => 'pushkar@cs.du.ac.in',
                ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('first_name', $e->errors());
        }

        $this->assertEquals(0, Scholar::count());
    }

    /** @test */
    public function request_validates_last_name_can_not_be_null()
    {
        $this->signIn();

        try {
            $this->withoutExceptionHandling()
                ->post(route('staff.scholars.create'), [
                    'first_name' => 'Pushkar',
                    'last_name' => '',
                    'email' => 'pushkar@cs.du.ac.in',
                ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('last_name', $e->errors());
        }

        $this->assertEquals(0, Scholar::count());
    }

    /** @test */
    public function request_validates_email_is_unique()
    {
        $this->signIn();

        $scholar = create(Scholar::class);

        try {
            $this->withoutExceptionHandling()
                ->post(route('staff.scholars.create'), [
                    'first_name' => 'Pushkar',
                    'last_name' => 'Sonkar',
                    'email' => $scholar->email,
                ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('email', $e->errors());
        }

        $this->assertEquals(1, Scholar::count());
    }
}
