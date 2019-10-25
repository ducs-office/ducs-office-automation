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
        $pdf = UploadedFile::fake()->create('document.pdf', 50);
        $scan = UploadedFile::fake()->image('testImage.jpg');
        $letter = create(OutgoingLetter::class);

        $reminder = [
            'date' => now()->subDay(1)->format('Y-m-d'),
            'letter_id' => $letter->id,
            'pdf' => $pdf,
            'scan' => $scan
        ];

        $this->withOutExceptionHandling()
            ->post('/reminders', $reminder);
        
        $this->assertEquals(1, LetterReminder::count());
        Storage::disk('local')->assertExists('letters/outgoing/reminders/'.$pdf->hashName()); 
        Storage::disk('local')->assertExists('letters/outgoing/reminders/'.$scan->hashName());
    }

    /** @test*/
    public function request_validates_letter_id_can_not_be_null()
    {
        $this->be(create(User::class));

        $pdf = UploadedFile::fake()->create('document.pdf', 50);
        $scan = UploadedFile::fake()->image('testImage.jpg');

        $reminder = [
            'date' => now()->subDay(1)->format('Y-m-d'),
            // 'letter_id' => $letter->id,
            'pdf' => $pdf,
            'scan' => $scan
        ];

        try{
            $this->withExceptionHandling()
                ->post('/reminders', $reminder);
        }catch(ValidationException $e) {
            $this->assertArrayHasKey('letter_id', $e->errors());
        }

        $this->assertEquals(0, LetterReminder::count());
    }

    /** @test */
    public function request_validates_letter_id_is_an_existing_letter()
    {
        $this->be(create(User::class));

        $pdf = UploadedFile::fake()->create('document.pdf', 50);
        $scan = UploadedFile::fake()->image('testImage.jpg');

        $reminder = [
            'date' => now()->subDay(1)->format('Y-m-d'),
            'letter_id' => 123,
            'pdf' => $pdf,
            'scan' => $scan
        ];

        try{
            $this->withExceptionHandling()
                ->post('/reminders', $reminder);
        }catch(ValidationException $e) {
            $this->assertArrayHasKey('letter_id', $e->errors());
        }

        $this->assertEquals(0, LetterReminder::count());
    }

    /** @test*/
    public function request_validates_only_pdf_can_be_uploaded()
    {
        Storage::fake();
        
        $this->be(create(User::class));
        $letter = create(OutgoingLetter::class);
        $pdf = UploadedFile::fake()->create('document.pdf', 50);

        $reminder = [
            'date' => now()->subDay(1)->format('Y-m-d'),
            'letter_id' => $letter->id,
            'pdf' => $pdf,
        ];


        $this->withOutExceptionHandling()
            ->post('/reminders', $reminder);
        
        $this->assertEquals(1, LetterReminder::count());
        Storage::disk('local')->assertExists('letters/outgoing/reminders/'.$pdf->hashName()); 
        
    }

    /** @test*/
    public function request_validates_only_scan_can_be_uploaded()
    {
        $this->be(create(User::class));
        $scan = UploadedFile::fake()->image('testImage.jpg');
        $letter = create(OutgoingLetter::class);
        $reminder = [
            'date' => now()->format('Y-m-d'),
            'letter_id' => $letter->id,
            'scan' => $scan
        ];

        $this->withOutExceptionHandling()
            ->post('/reminders', $reminder);
        
        $this->assertEquals(1, LetterReminder::count());
        Storage::disk('local')->assertExists('letters/outgoing/reminders/'.$scan->hashName());
    }


}
