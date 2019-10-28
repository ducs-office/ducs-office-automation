<?php

namespace Tests\Feature;

use App\LetterReminder;
use App\OutgoingLetter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

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
        Storage::fake();
        
        $this->be(create(User::class));
        $letter = create(OutgoingLetter::class);

        $reminder = [
            'date' => now()->subDay(1)->format('Y-m-d'),
            'letter_id' => $letter->id,
            'attachments' => [ 
                $photo = UploadedFile::fake()->image('Scanned.jpg'),
                $document = UploadedFile::fake()->create('Document.pdf') 
            ],
        ];

        $this->withOutExceptionHandling()
            ->post('/reminders', $reminder);
        
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

    /** @test*/
    public function request_validates_letter_id_can_not_be_null()
    {
        $this->be(create(User::class));

        $reminder = [
            'date' => now()->subDay(1)->format('Y-m-d'),
            // 'letter_id' => $letter->id,
            'attachments' => [ 
                UploadedFile::fake()->image('Scanned.jpg'),
                UploadedFile::fake()->create('Document.pdf') 
            ],
        ];

        try {
            $this->withExceptionHandling()
                ->post('/reminders', $reminder);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('letter_id', $e->errors());
        }

        $this->assertEquals(0, LetterReminder::count());
    }

    /** @test */
    public function request_validates_letter_id_is_an_existing_letter()
    {
        $this->be(create(User::class));

        $reminder = [
            'date' => now()->subDay(1)->format('Y-m-d'),
            'letter_id' => 123,
            'attachments' => [ 
                UploadedFile::fake()->image('Scanned.jpg'),
                UploadedFile::fake()->create('Document.pdf') 
            ],
        ];

        try {
            $this->withExceptionHandling()
                ->post('/reminders', $reminder);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('letter_id', $e->errors());
        }

        $this->assertEquals(0, LetterReminder::count());
    }
}
