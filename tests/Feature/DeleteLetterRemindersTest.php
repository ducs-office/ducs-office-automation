<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\LetterReminder;
use App\OutgoingLetter;
use App\User;

class DeleteLetterRemindersTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;
    
    /** @test */
    public function guest_cannot_delete_letter_reminder()
    {
        $reminder = create(LetterReminder::class);

        $this->withExceptionHandling()
            ->delete("/reminders/$reminder->id")
            ->assertRedirect('/login');
        
        $this->assertEquals(1, LetterReminder::count());
    }

    /** @test */
    public function user_can_delete_letter_reminder()
    {
        $this->be(create(User::class));
        $reminder = create(LetterReminder::class);

        $this->withoutExceptionHandling()
            ->delete("/reminders/$reminder->id");
        
        Storage::assertMissing($reminder->scan);
        Storage::assertMissing($reminder->pdf);
        $this->assertEquals(0, LetterReminder::count());
    }

}
