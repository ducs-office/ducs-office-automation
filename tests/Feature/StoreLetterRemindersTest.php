<?php

namespace Tests\Feature;

use App\Models\LetterReminder;
use App\Models\OutgoingLetter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

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
        $letter = create(OutgoingLetter::class);

        $this->withExceptionHandling()
            ->post(route('staff.outgoing_letters.reminders.store', $letter))
            ->assertRedirect();

        $this->assertEquals(0, LetterReminder::count());
    }

    /** @test */
    public function user_can_store_letter_reminders()
    {
        Storage::fake();

        $this->signIn(create(User::class), 'admin');

        $letter = create(OutgoingLetter::class, 1, [
            'creator_id' => auth()->id(),
        ]);

        $reminder = [
            'date' => now()->subDay(1)->format('Y-m-d'),
            'letter_id' => $letter->id,
            'attachments' => [
                $photo = UploadedFile::fake()->image('Scanned.jpg'),
                $document = UploadedFile::fake()->create('Document.pdf'),
            ],
        ];

        $this->withOutExceptionHandling()
            ->post(route('staff.outgoing_letters.reminders.store', $letter), $reminder);

        $this->assertEquals(1, LetterReminder::count());
        $reminder = LetterReminder::first();
        $this->assertCount(2, $reminder->attachments);
        $this->assertEquals(
            'letter_attachments/outgoing/reminders/' . $photo->hashName(),
            $reminder->attachments->first()->path
        );
        $this->assertEquals(
            'letter_attachments/outgoing/reminders/' . $document->hashName(),
            $reminder->attachments->last()->path
        );

        Storage::assertExists($reminder->attachments->first()->path);
        Storage::assertExists($reminder->attachments->last()->path);
    }

    /** @test */
    public function request_validates_atleast_on_attachment_is_uploaded()
    {
        $this->be(create(User::class));

        $letter = create(OutgoingLetter::class);

        $reminder = [
            'attachments' => [
            ],
        ];

        try {
            $this->withExceptionHandling()
                ->post(route('staff.outgoing_letters.reminders.store', $letter));
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('attachments', $e->errors());
        }

        $this->assertEquals(0, LetterReminder::count());
    }
}
