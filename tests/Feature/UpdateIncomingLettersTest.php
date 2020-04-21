<?php

namespace Tests\Feature;

use App\Models\IncomingLetter;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Concerns\ValidatesAttributes;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UpdateIncomingLettersTest extends TestCase
{
    use RefreshDatabase;

    protected function createLetterWithAttachment($count = 1, $overrides = [])
    {
        $letter = create(IncomingLetter::class, $count, $overrides);

        $letter->attachments()->create([
            'original_name' => 'Some random file.jpg',
            'path' => '/file/path.jpg',
        ]);

        return $letter;
    }

    /** @test */
    public function guest_can_not_update_any_incoming_letter()
    {
        $this->expectException(AuthenticationException::class);
        $letter = $this->createLetterWithAttachment();

        $this->withoutExceptionHandling()
            ->patch(route('staff.incoming_letters.update', $letter), ['date' => '2017-01-8'])
            ->assertRedirect(route('login_form'));

        $this->assertEquals($letter->date, $letter->fresh()->date);
    }

    /** @test */
    public function user_can_update_an_incoming_letter_in_database()
    {
        Storage::fake();

        $this->signIn();
        $receiver = create(User::class);
        $handovers = [create(User::class)->id, create(User::class)->id];
        $letter = create(IncomingLetter::class, 1, [
            'priority' => 2,
            'creator_id' => auth()->id(),
        ]);

        $new_incoming_letter = [
            'date' => '2019-04-02',
            'received_id' => 'Dept/RD/0002',
            'sender' => 'Exam Office',
            'recipient_id' => $receiver->id,
            'handovers' => $handovers,
            'priority' => 3,
            'subject' => 'foobar',
            'description' => 'lorem ipsum',
            'attachments' => [$file = UploadedFile::fake()->create('letter-scan.pdf')],
        ];

        $this->withoutExceptionHandling()
            ->patch(route('staff.incoming_letters.update', $letter), $new_incoming_letter)
            ->assertRedirect();

        $letter = $letter->fresh();

        $this->assertEquals($new_incoming_letter['date'], $letter['date']->format('Y-m-d'));
        $this->assertEquals($new_incoming_letter['received_id'], $letter['received_id']);
        $this->assertEquals($new_incoming_letter['sender'], $letter['sender']);
        $this->assertEquals($new_incoming_letter['recipient_id'], $letter['recipient_id']);
        $this->assertEquals($new_incoming_letter['priority'], $letter['priority']);
        $this->assertEquals($new_incoming_letter['subject'], $letter['subject']);
        $this->assertEquals($new_incoming_letter['description'], $letter['description']);

        $this->assertEquals('letter_attachments/incoming/' . $file->hashName(), $letter->attachments->last()->path);
        Storage::assertExists($letter->attachments->last()->path);
    }

    /** @test */
    public function user_can_not_update_creator_id_of_an_incoming_letter_in_database()
    {
        $this->signIn();
        $letter = $this->createLetterWithAttachment(1, [
            'creator_id' => auth()->id(),
        ]);

        $this->withoutExceptionHandling()
            ->patch(
                route('staff.incoming_letters.update', $letter),
                ['creator_id' => create(User::class)->id]
            )->assertRedirect();

        $this->assertEquals($letter->creator_id, $letter->fresh()->creator_id);
    }

    /** @test */
    public function request_validates_date_field_can_not_be_null()
    {
        $this->signIn();
        $letter = $this->createLetterWithAttachment(1, [
            'creator_id' => Auth::id(),
        ]);
        try {
            $this->withoutExceptionHandling()
            ->patch(route('staff.incoming_letters.update', $letter), ['date' => '']);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('date', $e->errors());
        }
        $this->assertEquals($letter->date, $letter->fresh()->date);
    }

    /** @test */
    public function request_validates_date_field_is_a_valid_date()
    {
        $this->signIn();
        $letter = $this->createLetterWithAttachment(1, [
            'creator_id' => auth()->id(),
        ]);
        $invalidDates = [
            '2014-16-14', //16 is not a valid month
            '2017-02-29', //not a leap year
            '2017-04-31', //31 date does not exist in 4th month
            '04-2017-12', // wrong format
        ];

        foreach ($invalidDates as $date) {
            try {
                $this->withoutExceptionHandling()
                ->patch(route('staff.incoming_letters.update', $letter), ['date' => $date]);

                $this->fail("Invalid date '{$date}' was not validated");
            } catch (ValidationException $e) {
                $this->assertArrayHasKey('date', $e->errors());
                $this->assertEquals($letter->date, $letter->fresh()->date);
            } catch (\Exception $e) {
                $this->fail("Invalid date '{$date}' was not validated");
            }
        }

        $validDates = [
            '2018-01-31',
            '2016-02-29',
            '2018-02-28',
            '2018-03-30',
        ];

        foreach ($validDates as $date) {
            $this->withoutExceptionHandling()
                ->patch(route('staff.incoming_letters.update', $letter), ['date' => $date])
                ->assertRedirect();

            $this->assertEquals($date, $letter->fresh()->date->format('Y-m-d'));
        }
    }

    /** @test */
    public function request_validates_date_field_cannot_be_a_future_date()
    {
        $this->signIn();
        $letter = $this->createLetterWithAttachment(1, [
            'creator_id' => auth()->id(),
        ]);

        try {
            $this->withoutExceptionHandling()
                ->patch(route('staff.incoming_letters.update', $letter), ['date' => $date = now()->addDay(1)->format('Y-m-d')]);

            $this->fail("Future date '{$date}' was not validated");
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('date', $e->errors());
            $this->assertEquals($letter->date, $letter->fresh()->date);
        } catch (\Exception $e) {
            $this->fail("Future date '{$date}' was not validated");
        }

        $date = now()->subDay(1)->format('Y-m-d');
        $this->withoutExceptionHandling()
            ->patch(route('staff.incoming_letters.update', $letter), ['date' => $date])
                ->assertRedirect();

        $this->assertEquals($date, $letter->fresh()->date->format('Y-m-d'));
    }

    /** @test */
    public function request_validates_sender_field_can_not_be_null()
    {
        $this->signIn();
        $letter = $this->createLetterWithAttachment(1, [
            'creator_id' => auth()->id(),
        ]);

        try {
            $this->withoutExceptionHandling()
                ->patch(route('staff.incoming_letters.update', $letter), ['sender' => '']);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('sender', $e->errors());
        }

        $this->assertEquals($letter->sender, $letter->fresh()->sender);
    }

    /** @test */
    public function request_validates_recipient_id_field_can_not_be_null()
    {
        $this->signIn();
        $letter = $this->createLetterWithAttachment(1, [
            'creator_id' => auth()->id(),
        ]);

        try {
            $this->withoutExceptionHandling()
                ->patch(route('staff.incoming_letters.update', $letter), ['recipient_id' => '']);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('recipient_id', $e->errors());
        }

        $this->assertEquals($letter->recipient_id, $letter->fresh()->recipient_id);
    }

    /** @test */
    public function request_validates_recipient_id_is_an_existing_user()
    {
        $this->signIn();
        $letter = $this->createLetterWithAttachment(1, [
            'creator_id' => auth()->id(),
        ]);

        try {
            $this->withoutExceptionHandling()
            ->patch(route('staff.incoming_letters.update', $letter), ['recipient_id' => 4]);

            $this->fail("Failed to validate \'recipient_id\' is an existing user");
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('recipient_id', $e->errors());
        }

        $this->assertEquals($letter->recipient_id, $letter->fresh()->recipient_id);
    }

    /** @test */
    public function request_validates_handover_id_is_an_existing_user()
    {
        $this->signIn();
        $letter = $this->createLetterWithAttachment(1, [
            'creator_id' => auth()->id(),
        ]);

        try {
            $this->withoutExceptionHandling()
            ->patch(route('staff.incoming_letters.update', $letter), ['handovers' => [4]]);

            $this->fail("Failed to validate \'handover_id\' is an existing user");
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('handovers.0', $e->errors());
        }

        $this->assertEquals($letter->handover_id, $letter->fresh()->handover_id);
    }

    /** @test */
    public function request_validates_handover_id_field_can_be_null()
    {
        $this->signIn();
        $letter = $this->createLetterWithAttachment(1, [
            'creator_id' => auth()->id(),
        ]);

        $this->withoutExceptionHandling()
            ->patch(route('staff.incoming_letters.update', $letter), ['handovers' => []])
            ->assertRedirect();

        $this->assertNull($letter->fresh()->handover_id);
    }

    /** @test */
    public function request_validates_priority_field_can_be_null()
    {
        $this->signIn();
        $letter = $this->createLetterWithAttachment(1, [
            'creator_id' => auth()->id(),
        ]);

        $this->withoutExceptionHandling()
            ->patch(route('staff.incoming_letters.update', $letter), ['priority' => ''])
            ->assertRedirect();

        $this->assertNull($letter->fresh()->priority);
    }

    /** @test */
    public function request_validates_subject_field_can_not_be_null()
    {
        $this->signIn();
        $letter = $this->createLetterWithAttachment(1, [
            'creator_id' => auth()->id(),
        ]);

        try {
            $this->withoutExceptionHandling()
                ->patch(route('staff.incoming_letters.update', $letter), ['subject' => '']);

            $this->fail('Empty \'subject\' was not validated.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('subject', $e->errors());
        }

        $this->assertEquals($letter->subject, $letter->fresh()->subject);
    }

    /** @test */
    public function request_validates_subject_field_maxlimit_100()
    {
        $this->signIn();
        $letter = $this->createLetterWithAttachment(1, [
            'creator_id' => auth()->id(),
        ]);

        try {
            $this->withoutExceptionHandling()
                ->patch(route('staff.incoming_letters.update', $letter), ['subject' => Str::random(101)]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('subject', $e->errors());
        }

        $this->assertEquals($letter->subject, $letter->fresh()->subject);
    }

    /** @test */
    public function request_validates_description_field_can_be_null()
    {
        $this->signIn();
        $letter = $this->createLetterWithAttachment(1, [
            'creator_id' => auth()->id(),
        ]);

        $this->withoutExceptionHandling()
            ->patch(route('staff.incoming_letters.update', $letter), ['description' => ''])
            ->assertRedirect();

        $this->assertNull($letter->fresh()->description);
    }

    /** @test */
    public function request_validates_description_field_maxlimit_400()
    {
        $this->signIn();
        $letter = $this->createLetterWithAttachment(1, [
            'creator_id' => auth()->id(),
        ]);

        try {
            $this->withoutExceptionHandling()
                ->patch(route('staff.incoming_letters.update', $letter), ['description' => Str::random(401)]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('description', $e->errors());
        }

        $this->assertEquals($letter->description, $letter->fresh()->description);
    }
}
