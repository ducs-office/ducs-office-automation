<?php

namespace Tests\Feature;

use App\LetterReminder;
use App\OutgoingLetter;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

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
            ->delete(route('staff.reminders.destroy', $reminder))
            ->assertRedirect(route('login_form'));

        $this->assertEquals(1, LetterReminder::count());
    }

    /** @test */
    public function user_can_delete_letter_reminder()
    {
        $this->signIn(create(User::class), 'admin');

        $letter = create(OutgoingLetter::class, 1, [
            'creator_id' => auth()->id(),
        ]);

        $reminder = create(LetterReminder::class, 1, [
            'letter_id' => $letter->id,
        ]);

        $this->withoutExceptionHandling()
            ->delete(route('staff.reminders.destroy', $reminder));

        Storage::assertMissing($reminder->scan);
        Storage::assertMissing($reminder->pdf);

        $this->assertEquals(0, LetterReminder::count());
    }
}
