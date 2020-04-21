<?php

namespace Tests\Feature;

use App\Models\OutgoingLetter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class StoreOutgoingLettersTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake();
    }

    /** @test */
    public function guest_cannot_store_outgoing_letters()
    {
        $this->withExceptionHandling()
            ->post(route('staff.outgoing_letters.store'))
            ->assertRedirect(route('login_form'));

        $this->assertEquals(0, OutgoingLetter::count());
    }

    /** @test */
    public function store_outgoing_letter_in_database()
    {
        $this->signIn();

        $letter = [
            'date' => now()->format('Y-m-d'),
            'subject' => $this->faker->words(3, true),
            'recipient' => $this->faker->name(),
            'type' => 'Bill',
            'amount' => $this->faker->randomFloat,
            'sender_id' => create(User::class)->id,
            'attachments' => [
                $pdfFile = UploadedFile::fake()->create('document.pdf'),
                $scanFile = UploadedFile::fake()->image('scanned_copy.jpg'),
            ],
        ];

        $this->withoutExceptionHandling()
            ->post(route('staff.outgoing_letters.store'), $letter)
            ->assertRedirect();

        $this->assertEquals(1, OutgoingLetter::count());

        tap(OutgoingLetter::first(), function ($letter) use ($pdfFile, $scanFile) {
            $this->assertCount(2, $letter->attachments);
            $this->assertEquals('letter_attachments/outgoing/' . $pdfFile->hashName(), $letter->attachments[0]->path);
            $this->assertEquals('letter_attachments/outgoing/' . $scanFile->hashName(), $letter->attachments[1]->path);
            Storage::assertExists('letter_attachments/outgoing/' . $pdfFile->hashName());
            Storage::assertExists('letter_attachments/outgoing/' . $scanFile->hashName());
        });
    }

    /** @test */
    public function request_validates_date_field_is_not_null()
    {
        try {
            $this->signIn();

            $letter = [
                // 'date' => now()->format('Y-m-d'),
                'subject' => $this->faker->words(3, true),
                'recipient' => $this->faker->name(),
                'type' => 'Bill',
                'amount' => $this->faker->randomFloat,
                'sender_id' => create(User::class)->id,
                'attachments' => [UploadedFile::fake()->create('document.pdf')],
            ];

            $this->withoutExceptionHandling()
                ->post(route('staff.outgoing_letters.store'), $letter);

            $this->fail('Empty date field was not validated.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('date', $e->errors());
            $this->assertEquals(0, OutgoingLetter::count());
        }
    }

    /** @test */
    public function request_validates_date_field_is_a_valid_date()
    {
        $this->signIn();

        $letter = [
            'date' => now()->format('Y-m-d'),
            'subject' => $this->faker->words(3, true),
            'recipient' => $this->faker->name(),
            'type' => 'Bill',
            'amount' => $this->faker->randomFloat,
            'sender_id' => create(User::class)->id,
            'attachments' => [UploadedFile::fake()->create('document.pdf')],
        ];

        $invalidDates = [
            '2014-16-14', //16 is not a valid month
            '2017-02-29', //not a leap year
            '2017-04-31', //31 date does not exist in 4rd month
        ];

        $validDates = [
            '2018-01-31',
            '2016-02-29',
            '2018-02-28',
            '2018-03-31',
        ];

        foreach ($invalidDates as $date) {
            try {
                $letter['date'] = $date;
                $this->withoutExceptionHandling()
                    ->post(route('staff.outgoing_letters.store'), $letter);
                $this->fail("Invalid date '{$date}' was not validated");
            } catch (ValidationException $e) {
                $this->assertArrayHasKey('date', $e->errors());
                $this->assertEquals(0, OutgoingLetter::count());
            }
        }

        foreach ($validDates as $date) {
            $letter['date'] = $date;
            $this->withoutExceptionHandling()
                ->post(route('staff.outgoing_letters.store'), $letter)
                ->assertRedirect();
            $this->assertEquals(1, OutgoingLetter::count());
            OutgoingLetter::truncate();
        }
    }

    /** @test */
    public function request_validates_date_field_cannot_be_a_future_date()
    {
        $this->signIn();

        $letter = [
            'date' => now()->addMonth(2)->format('Y-m-d'),
            'subject' => $this->faker->words(3, true),
            'recipient' => $this->faker->name(),
            'type' => 'Bill',
            'amount' => $this->faker->randomFloat,
            'sender_id' => create(User::class)->id,
            'attachments' => [UploadedFile::fake()->create('document.pdf')],
        ];

        try {
            $this->withoutExceptionHandling()
                ->post(route('staff.outgoing_letters.store'), $letter);

            $this->fail("Future date '{$letter['date']}' was not validated");
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('date', $e->errors());
            $this->assertEquals(0, OutgoingLetter::count());
        } catch (\Exception $e) {
            $this->fail("Future date '{$letter['date']}' was not validated");
        }

        $letter['date'] = now()->subMonth(1)->format('Y-m-d');

        $this->withoutExceptionHandling()
            ->post(route('staff.outgoing_letters.store'), $letter)
            ->assertRedirect();

        $this->assertEquals(1, OutgoingLetter::count());
    }

    /** @test */
    public function request_validates_type_field_is_not_null()
    {
        try {
            $this->signIn();

            $letter = [
                'date' => now()->format('Y-m-d'),
                'subject' => $this->faker->words(3, true),
                'recipient' => $this->faker->name(),
                'type' => '', // Empty type
                'amount' => $this->faker->randomFloat,
                'sender_id' => create(User::class)->id,
                'attachments' => [UploadedFile::fake()->create('document.pdf')],
            ];

            $this->withoutExceptionHandling()
                ->post(route('staff.outgoing_letters.store'), $letter);

            $this->fail('Empty \'type\' field was not validated.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('type', $e->errors());
            $this->assertEquals(0, OutgoingLetter::count());
        } catch (\Exception $e) {
            $this->fail('Empty \'type\' field was not validated.');
        }
    }

    /** @test */
    public function request_validates_subject_field_is_not_null()
    {
        try {
            $this->signIn();

            $letter = [
                'date' => now()->format('Y-m-d'),
                'subject' => '', //Empty
                'recipient' => $this->faker->name(),
                'type' => 'Bill',
                'amount' => $this->faker->randomFloat,
                'sender_id' => create(User::class)->id,
                'attachments' => [UploadedFile::fake()->create('document.pdf')],
            ];

            $this->withoutExceptionHandling()
                ->post(route('staff.outgoing_letters.store'), $letter);

            $this->fail('Empty \'subject\' field was not validated.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('subject', $e->errors());
        }

        $this->assertEquals(0, OutgoingLetter::count());
    }

    /** @test */
    public function request_validates_subject_field_maxlimit_100()
    {
        try {
            $this->signIn();
            $letter = [
                'date' => now()->format('Y-m-d'),
                'subject' => $this->faker->regexify('[A-Za-z0-9]{101}'),
                'recipient' => $this->faker->name(),
                'type' => 'Bill',
                'amount' => $this->faker->randomFloat,
                'sender_id' => create(User::class)->id,
                'attachments' => [UploadedFile::fake()->create('document.pdf')],
            ];

            $this->withoutExceptionHandling()
                ->post(route('staff.outgoing_letters.store'), $letter);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('subject', $e->errors());
        }

        $this->assertEquals(0, OutgoingLetter::count());
    }

    /** @test */
    public function request_validates_recipient_field_is_not_null()
    {
        try {
            $this->signIn();
            $letter = [
                'date' => now()->format('Y-m-d'),
                'subject' => $this->faker->words(3, true),
                'recipient' => '', // Empty
                'type' => 'Bill',
                'amount' => $this->faker->randomFloat,
                'sender_id' => create(User::class)->id,
                'attachments' => [UploadedFile::fake()->create('document.pdf')],
            ];

            $this->withoutExceptionHandling()
                ->post(route('staff.outgoing_letters.store'), $letter);

            $this->fail('Empty \'recipient\' field was not validated.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('recipient', $e->errors());
            $this->assertEquals(0, OutgoingLetter::count());
        } catch (\Exception $e) {
            $this->fail('Empty \'recipient\' field was not validated.');
        }
    }

    /** @test */
    public function request_validates_sender_id_field_is_not_null()
    {
        try {
            $this->signIn();
            $letter = [
                'date' => now()->format('Y-m-d'),
                'subject' => $this->faker->words(3, true),
                'recipient' => $this->faker->name(),
                'type' => 'Bill',
                'amount' => $this->faker->randomFloat,
                'sender_id' => '', // Empty type
                'attachments' => [UploadedFile::fake()->create('document.pdf')],
            ];

            $this->withoutExceptionHandling()
                ->post(route('staff.outgoing_letters.store'), $letter);

            $this->fail('Empty \'sender_id\' field was not validated.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('sender_id', $e->errors());
            $this->assertEquals(0, OutgoingLetter::count());
        } catch (\Exception $e) {
            $this->fail('Empty \'sender_id\' field was not validated.');
        }
    }

    /** @test */
    public function request_validates_sender_id_field_must_be_a_existing_user()
    {
        try {
            $this->signIn();
            $letter = [
                'date' => now()->format('Y-m-d'),
                'subject' => $this->faker->words(3, true),
                'recipient' => $this->faker->name(),
                'type' => 'Bill',
                'amount' => $this->faker->randomFloat,
                'sender_id' => 123,
                'attachments' => [UploadedFile::fake()->create('document.pdf')],
            ];

            $this->withoutExceptionHandling()
                ->post(route('staff.outgoing_letters.store'), $letter);

            $this->fail('Failed to validate \'sender_id\' is a valid existing user id');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('sender_id', $e->errors());
            $this->assertEquals(0, OutgoingLetter::count());
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /** @test */
    public function request_validates_description_field_can_be_null()
    {
        $this->signIn();

        $letter = [
            'date' => now()->format('Y-m-d'),
            'subject' => $this->faker->words(3, true),
            'recipient' => $this->faker->name(),
            'type' => 'Bill',
            // 'description' => $this->faker->sentence(),
            'amount' => $this->faker->randomFloat,
            'sender_id' => create(User::class)->id,
            'attachments' => [UploadedFile::fake()->create('document.pdf')],
        ];

        $this->withoutExceptionHandling()
            ->post(route('staff.outgoing_letters.store'), $letter)
            ->assertRedirect();

        $this->assertEquals(1, OutgoingLetter::count());
    }

    /** @test */
    public function request_validates_amount_field_can_be_null()
    {
        $this->signIn();

        $letter = [
            'date' => now()->format('Y-m-d'),
            'subject' => $this->faker->words(3, true),
            'recipient' => $this->faker->name(),
            'type' => 'Bill',
            'amount' => '', // empty string amount
            'sender_id' => create(User::class)->id,
            'attachments' => [UploadedFile::fake()->create('document.pdf')],
        ];

        $this->withoutExceptionHandling()
            ->post(route('staff.outgoing_letters.store'), $letter)
            ->assertRedirect();

        $this->assertEquals(1, OutgoingLetter::count());
    }

    /** @test */
    public function request_validates_amount_field_cannot_be_a_string_value()
    {
        try {
            $this->signIn();

            $letter = [
                'date' => now()->format('Y-m-d'),
                'subject' => $this->faker->words(3, true),
                'recipient' => $this->faker->name(),
                'type' => 'Bill',
                'amount' => 'some string',
                'sender_id' => create(User::class)->id,
                'attachments' => [UploadedFile::fake()->create('document.pdf')],
            ];

            $this->withoutExceptionHandling()
                        ->post(route('staff.outgoing_letters.store'), $letter);

            $this->fail('Failed to validate \'amount\' cannot be a string value');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('amount', $e->errors());
            $this->assertEquals(0, OutgoingLetter::count());
        } catch (\Exception $e) {
            $this->fail('Failed to validate \'sender_id\' cannot be a string value');
        }
    }
}
