<?php

namespace Tests\Feature;

use \App\User;
use App\IncomingLetter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StoreIncomingLettters extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function guest_cannot_store_incoming_letters()
    {
        $this->withExceptionHandling()
            ->post('/incoming-letters')
            ->assertRedirect('/login');
        
        $this->assertEquals(IncomingLetter::count(), 0);
    }

    /** @test */
    public function user_can_store_incoming_letters()
    {
        $this->signIn();

        $letter = [
            'date' => now()->format('Y-m-d'),
            'received_id' => 'CS/xyz/2019/12',
            'sender' => $this->faker->name(),
            'recipient_id' => create(User::class)->id,
            'handover_id' => create(User::class)->id,
            'priority' => 2,
            'subject' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(),
            'attachments' => [ $scanFile = UploadedFile::fake()->image('scanned_copy.jpg')]
        ];

        $this->withoutExceptionHandling()
            ->post('/incoming-letters', $letter)
            ->assertRedirect('/incoming-letters');

        $this->assertEquals(IncomingLetter::count(), 1);

        tap(IncomingLetter::first(), function($letter) use ($scanFile) {
            $this->assertCount(1, $letter->attachments);
            $this->assertEquals('letter_attachments/incoming/' . $scanFile->hashName(), $letter->attachments[0]->path);
            Storage::assertExists('letter_attachments/incoming/' . $scanFile->hashName());
        });
    }

    /** @test */
    public function request_validates_date_field_is_not_null()
    {
        try 
        {
            $this->signIn();
            $letter = [
                'date' => '',
                'received_id' => 'CS/xyz/2019/12',
                'sender' => $this->faker->name(),
                'recipient_id' => create(User::class)->id,
                'handover_id' => create(User::class)->id,
                'priority' => 2,
                'subject' => $this->faker->words(3, true),
                'description' => $this->faker->paragraph(),
                'attachments' =>  [UploadedFile::fake()->create('document.pdf')]
            ];
        
            $this->withoutExceptionHandling()
                ->post('/incoming-letters', $letter);

        } catch(ValidationException $e) {
            $this->assertArrayHasKey('date', $e->errors());
        }

        $this->assertEquals(IncomingLetter::count(), 0);
    }

    /** @test */
    public function request_validates_date_field_is_a_valid_date()
    {
        $this->signIn();
        $letter = [
            'received_id' => 'CS/xyz/2019/12',
            'sender' => $this->faker->name(),
            'recipient_id' => create(User::class)->id,
            'handover_id' => create(User::class)->id,
            'priority' => 2,
            'subject' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(),
            'attachments' =>  [UploadedFile::fake()->create('document.pdf')]
        ];

        $invalid_dates = [
            '2007-17-31',
            '2003-11-31',
            '2013-2-29'
        ];
        $valid_dates = [
            '2007-12-31',
            '2009-11-30',
            '2012-2-29'
        ];

        foreach($invalid_dates as $date)
        {
            try
            {
                $letter['date'] = $date;

                $this->withoutExceptionHandling()
                    ->post('/incoming-letters', $letter);
            } catch(ValidationException $e) {
                $this->assertArrayHasKey('date', $e->errors());
            }

            $this->assertEquals(IncomingLetter::count(), 0);
        }

        foreach($valid_dates as $date)
        {
            $letter['date'] = $date;

            $this->withoutExceptionHandling()
                ->post('/incoming-letters', $letter)
                ->assertRedirect('/incoming-letters');

            $this->assertEquals(IncomingLetter::count(), 1);

            IncomingLetter::truncate();
        }
    }

    /** @test */
    public function request_validates_date_field_is_not_a_future_date()
    {
        try
        {
            $this->signIn();
            $letter = [
                'date' => now()->addDay()->format('Y-m-d'),
                'received_id' => 'CS/xyz/2019/12',
                'sender' => $this->faker->name(),
                'recipient_id' => create(User::class)->id,
                'handover_id' => create(User::class)->id,
                'priority' => 2,
                'subject' => $this->faker->words(3, true),
                'description' => $this->faker->paragraph(),
                'attachments' =>  [UploadedFile::fake()->create('document.pdf')]
            ];

            $this->withoutExceptionHandling()
                ->post('/incoming-letters', $letter);

        } catch(ValidationException $e) {
            $this->assertArrayHasKey('date', $e->errors());
        }
        
        $this->assertEquals(IncomingLetter::count(), 0);
    }

    /** @test */
    public function request_validates_received_id_field_is_not_null()
    {
        try
        {
            $this->signIn();
            $letter = [
                'date' => now()->format('Y-m-d'),
                'received_id' => '',
                'sender' => $this->faker->name(),
                'recipient_id' => create(User::class)->id,
                'handover_id' => create(User::class)->id,
                'priority' => 2,
                'subject' => $this->faker->words(3, true),
                'description' => $this->faker->paragraph(),
                'attachments' =>  [UploadedFile::fake()->create('document.pdf')]
            ];

            $this->withoutExceptionHandling()
                ->post('/incoming-letters', $letter);
        } catch(ValidationException $e) {
            $this->assertArrayHasKey('received_id', $e->errors());
        }

        $this->assertEquals(IncomingLetter::count(), 0);
    }

    /** @test */
    public function request_validates_sender_field_is_not_null() 
    {
        try
        {
            $this->signIn();
            $letter = [
                'date' => now()->format('Y-m-d'),
                'received_id' => 'CS/xyz/2019/12',
                'sender' => '',
                'recipient_id' => create(User::class)->id,
                'handover_id' => create(User::class)->id,
                'priority' => 2,
                'subject' => $this->faker->words(3, true),
                'description' => $this->faker->paragraph(),
                'attachments' =>  [UploadedFile::fake()->create('document.pdf')]
            ];

            $this->withoutExceptionHandling()
                ->post('/incoming-letters', $letter);
        } catch(ValidationException $e) {
            $this->assertArrayHasKey('sender', $e->errors());
        }

        $this->assertEquals(IncomingLetter::count(), 0);
    }

    /** @test */
    public function request_validates_sender_field_max_length_is_50()
    {
        try
        {
            $this->signIn();
            $letter = [
                'date' => now()->format('Y-m-d'),
                'received_id' => 'CS/xyz/2019/12',
                'sender' => $this->faker->regexify('[A-Ba-b0-9]{51}'),
                'recipient_id' => create(User::class)->id,
                'handover_id' => create(User::class)->id,
                'priority' => 2,
                'subject' => $this->faker->words(3, true),
                'description' => $this->faker->paragraph(),
                'attachments' =>  [UploadedFile::fake()->create('document.pdf')]
            ];

            $this->withoutExceptionHandling()
                ->post('/incoming-letters', $letter);
        } catch(ValidationException $e) {
            $this->assertArrayHasKey('sender', $e->errors());
        }

        $this->assertEquals(IncomingLetter::count(), 0);
    }

    /** @test */
    public function request_validates_recipient_id_field_is_not_null()
    {
        try
        {
            $this->signIn();
            $letter = [
                'date' => now()->format('Y-m-d'),
                'received_id' => 'CS/xyz/2019/12',
                'sender' => $this->faker->name(),
                'recipient_id' => '',
                'handover_id' => create(User::class)->id,
                'priority' => 2,
                'subject' => $this->faker->words(3, true),
                'description' => $this->faker->paragraph(),
                'attachments' =>  [UploadedFile::fake()->create('document.pdf')]
            ];

            $this->withoutExceptionHandling()
                ->post('/incoming-letters', $letter);

        } catch(ValidationException $e) {
            $this->assertArrayHasKey('recipient_id', $e->errors());
        }

        $this->assertEquals(IncomingLetter::count(), 0);
    }

    /** @test */
    public function request_validates_recipient_id_field_is_existing_user()
    {
        try
        {
            $this->signIn();
            $letter = [
                'date' => now()->format('Y-m-d'),
                'received_id' => 'CS/xyz/2019/12',
                'sender' => $this->faker->name(),
                'recipient_id' => 15,
                'handover_id' => create(User::class)->id,
                'priority' => 2,
                'subject' => $this->faker->words(3, true),
                'description' => $this->faker->paragraph(),
                'attachments' =>  [UploadedFile::fake()->create('document.pdf')]
            ];

            $this->withoutExceptionHandling()
                ->post('/incoming-letters', $letter);
        } catch(ValidationException $e) {
            $this->assertArrayHasKey('recipient_id', $e->errors());
        }

        $this->assertEquals(IncomingLetter::count(), 0);
    }

    /** @test */
    public function request_validates_handover_id_can_be_null()
    {
        $this->signIn();
        $letter = [
            'date' => now()->format('Y-m-d'),
            'received_id' => 'CS/xyz/2019/12',
            'sender' => $this->faker->name(),
            'recipient_id' => create(User::class)->id,
            'handover_id' => '',
            'priority' => 2,
            'subject' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(),
            'attachments' =>  [UploadedFile::fake()->create('document.pdf')]
        ];

        $this->withoutExceptionHandling()
            ->post('/incoming-letters', $letter)
            ->assertRedirect('/incoming-letters');
            
        $this->assertEquals(IncomingLetter::count(), 1);
    }

    /** @test */
    public function request_validates_handover_id_field_is_existing_user()
    {
        try
        {
            $this->signIn();
            $letter = [
                'date' => now()->format('Y-m-d'),
                'received_id' => 'CS/xyz/2019/12',
                'sender' => $this->faker->name(),
                'recipient_id' => create(User::class)->id,
                'handover_id' => 51,
                'priority' => 2,
                'subject' => $this->faker->words(3, true),
                'description' => $this->faker->paragraph(),
                'attachments' =>  [UploadedFile::fake()->create('document.pdf')]
            ];

            $this->withoutExceptionHandling()
                ->post('/incoming-letters', $letter);

        } catch(ValidationException $e) {
            $this->assertArrayHasKey('handover_id', $e->errors());
        }
        
        $this->assertEquals(IncomingLetter::count(), 0);
    }

    /** @test */
    public function request_validates_priority_field_can_be_null()
    {
        $this->signIn();
        $letter = [
            'date' => now()->format('Y-m-d'),
            'received_id' => 'CS/xyz/2019/12',
            'sender' => $this->faker->name(),
            'recipient_id' => create(User::class)->id,
            'handover_id' => create(User::class)->id,
            'priority' => '',
            'subject' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(),
            'attachments' =>  [UploadedFile::fake()->create('document.pdf')]
        ];

        $this->withoutExceptionHandling()
            ->post('/incoming-letters', $letter)
            ->assertRedirect('/incoming-letters');

        $this->assertEquals(IncomingLetter::count(), 1);
    }

    /** @test */
    public function request_validates_priority_field_is_valid_priority()
    {
        try
        {
            $this->signIn();
            $letter = [
                'date' => now()->format('Y-m-d'),
                'received_id' => 'CS/xyz/2019/12',
                'sender' => $this->faker->name(),
                'recipient_id' => create(User::class)->id,
                'handover_id' => create(User::class)->id,
                'priority' => $this->faker->randomNumber()+3,
                'subject' => $this->faker->words(3, true),
                'description' => $this->faker->paragraph(),
                'attachments' =>  [UploadedFile::fake()->create('document.pdf')]
            ];

            $this->withoutExceptionHandling()
                ->post('/incoming-letters', $letter);

        } catch(ValidationException $e) {
            $this->assertArrayHasKey('priority', $e->errors());
        }

        $this->assertEquals(IncomingLetter::count(), 0);
    }

    /** @test */
    public function request_validates_subject_field_is_not_null()
    {
        try
        {
            $this->signIn();
            $letter = [
                'date' => now()->format('Y-m-d'),
                'received_id' => 'CS/xyz/2019/12',
                'sender' => $this->faker->name(),
                'recipient_id' => create(User::class)->id,
                'handover_id' => create(User::class)->id,
                'priority' => 2,
                'subject' => '',
                'description' => $this->faker->paragraph(),
                'attachments' =>  [UploadedFile::fake()->create('document.pdf')]
            ];

            $this->withoutExceptionHandling()
                ->post('/incoming-letters', $letter);

        } catch(ValidationException $e) {
            $this->assertArrayHasKey('subject', $e->errors());
        }

        $this->assertEquals(IncomingLetter::count(), 0);
    }

    /** @test */
    public function request_validates_subject_field_max_length_is_80()
    {
        try
        {
            $this->signIn();
            $letter = [
                'date' => now()->format('Y-m-d'),
                'received_id' => 'CS/xyz/2019/12',
                'sender' => $this->faker->name(),
                'recipient_id' => create(User::class)->id,
                'handover_id' => create(User::class)->id,
                'priority' => 2,
                'subject' => $this->faker->regexify('[A-Ba-b0-9]{81}'),
                'description' => $this->faker->paragraph(),
                'attachments' =>  [UploadedFile::fake()->create('document.pdf')]
            ];

            $this->withoutExceptionHandling()
                ->post('/incoming-letters', $letter);

        } catch(ValidationException $e) {
            $this->assertArrayHasKey('subject', $e->errors());
        }

        $this->assertEquals(IncomingLetter::count(), 0);
    }

    /** @test */
    public function request_validates_description_field_can_be_null()
    {
        $this->signIn();
        $letter = [
            'date' => now()->format('Y-m-d'),
            'received_id' => 'CS/xyz/2019/12',
            'sender' => $this->faker->name(),
            'recipient_id' => create(User::class)->id,
            'handover_id' => create(User::class)->id,
            'priority' => 2,
            'subject' => $this->faker->words(3, true),
            'description' => '',
            'attachments' =>  [UploadedFile::fake()->create('document.pdf')]
        ];

        $this->withoutExceptionHandling()
            ->post('/incoming-letters', $letter)
            ->assertRedirect('/incoming-letters');

        $this->assertEquals(IncomingLetter::count(), 1);
    }

    /** @test */
    public function request_validates_description_field_max_length_is_400()
    {
        try
        {
            $this->signIn();
            $letter = [
                'date' => now()->format('Y-m-d'),
                'received_id' => 'CS/xyz/2019/12',
                'sender' => $this->faker->name(),
                'recipient_id' => create(User::class)->id,
                'handover_id' => create(User::class)->id,
                'priority' => 2,
                'subject' => $this->faker->words(3, true),
                'description' => $this->faker->regexify('[A-Ba-b0-9]{401}'),
                'attachments' =>  [UploadedFile::fake()->create('document.pdf')]
            ];

            $this->withoutExceptionHandling()
                ->post('/incoming-letters', $letter);

        } catch(ValidationException $e) {
            $this->assertArrayHasKey('description', $e->errors());
        }

        $this->assertEquals(IncomingLetter::count(), 0);
    }
}