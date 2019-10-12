<?php

namespace Tests\Feature;

use App\LetterReminder;
use App\OutgoingLetter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use Dotenv\Exception\ValidationException;

class StoreLetterRemindersTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /** @test */
    public function guest_can_not_store_letter_reminders()
    {
        $this->withExceptionHandling()
            ->post('/reminders')
            ->assertRedirect('login');

        $this->assertEquals(0, LetterReminder::count());
    }

    /** @test */
    public function user_can_store_letter_reminders() 
    {
        $this->be(factory(User::class)->create());
        $letter = factory(OutgoingLetter::class)->create();
        $reminder = factory(LetterReminder::class)->make([
            'letter_id' => $letter->id
        ]);

        $this->withOutExceptionHandling()
            ->post('/reminders', $reminder->toArray());

        $this->assertEquals(1,LetterReminder::count());
            
    }

    /** @test*/
    public function request_validates_letter_id_can_not_be_null()
    {
        $this->be(factory(User::class)->create());
        $reminder = factory(LetterReminder::class)->make(['letter_id' => '']);
        
        try{
            $this->withExceptionHandling()
                ->post('/reminders', $reminder->toArray());
        }catch(ValidationException $e) {
            $this->assertArrayHasKey('letter_id', $e->errors());
        }

        $this->assertEquals(0, LetterReminder::count());
    }

    /** @test */
    public function request_validates_letter_id_is_an_existing_letter()
    {
        $this->be(factory(User::class)->create());
        $reminder = factory(LetterReminder::class)->make(['letter_id' => 4]);

        try{
            $this->withExceptionHandling()
                ->post('/reminders', $reminder->toArray());
        }catch(ValidationException $e) {
            $this->assertArrayHasKey('letter_id', $e->errors());
        }

        $this->assertEquals(0, LetterReminder::count());
    }

}
