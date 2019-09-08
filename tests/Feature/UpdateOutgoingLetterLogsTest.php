<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\OutgoingLetterLog;
use App\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateOutgoingLetterLogsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_update_letter_logs()
    {
        $letter = factory(OutgoingLetterLog::class)->create();
        $this->withExceptionHandling()
            ->patch("/outgoing-letter-logs/{$letter->id}", ['date' => '2018-08-9'])
            ->assertRedirect('/login');
            
        $this->assertEquals($letter->date, $letter->fresh()->date);
    }

    /** @test */
    public function user_can_update_outgoing_letter_log_in_database()
    {
        $letter = factory(OutgoingLetterLog::class)->create();
        $this->be(factory(User::class)->create());
        
        $new_letter_log = factory(OutgoingLetterLog::class)->make();

        $this->withoutExceptionHandling()
            ->patch(
                "/outgoing-letter-logs/{$letter->id}", 
                $new_letter_log->toArray()
            )->assertRedirect('/outgoing-letter-logs');
            
        $this->assertArraySubset($new_letter_log->toArray(), $letter->fresh()->toArray());
    }

    /** @test */
    public function request_validates_date_field_is_not_null()
    {
        $letter = factory(OutgoingLetterLog::class)->create();
        try {
            $this->be(factory(\App\User::class)->create());
        
            $this->withoutExceptionHandling()
                ->patch("/outgoing-letter-logs/{$letter->id}", ['date' => '']);
            
            $this->fail('Empty date field was not validated.');
        } catch(ValidationException $e) {
            $this->assertArrayHasKey('date', $e->errors());
            $this->assertEquals($letter->date, $letter->fresh()->date);
        }
    }

    /** @test */
    public function request_validates_date_field_is_a_valid_date()
    {
        $letter = factory(OutgoingLetterLog::class)->create();
        $this->be(factory(\App\User::class)->create());

        $invalidDates = [
            '2014-16-14', //16 is not a valid month
            '2017-02-29', //not a leap year
            '2017-03-31', //31 date does not exist in 3rd month
        ];

        $validDates = [
            '2018-01-31',
            '2016-02-29',
            '2018-02-28',
            '2018-03-30',
        ];

        foreach ($invalidDates as $date) {
            try {
                $this->withoutExceptionHandling()
                    ->patch("/outgoing-letter-logs/{$letter->id}", compact('date'));
                        
                $this->fail("Invalid date '{$date}' was not validated");
            } catch (ValidationException $e) {
                $this->assertArrayHasKey('date', $e->errors());
                $this->assertEquals($letter->date, $letter->fresh()->date);
            } catch (\Exception $e) {
                $this->fail("Invalid date '{$date}' was not validated");
            }
        }

        foreach( $validDates as $date) {
            $this->withoutExceptionHandling()
                ->patch("/outgoing-letter-logs/{$letter->id}", compact('date'))
                ->assertRedirect('/outgoing-letter-logs');

            $this->assertEquals($date, $letter->fresh()->date);
        }
    }

    /** @test */
    public function request_validates_date_field_cannot_be_a_future_date()
    {
        $letter = factory(OutgoingLetterLog::class)->create();
        $this->be(factory(\App\User::class)->create());

        try {

            $this->withoutExceptionHandling()
                ->patch("/outgoing-letter-logs/{$letter->id}", [
                    'date' => $date = now()->addMonth(1)->format('Y-m-d')
                ]);

            $this->fail("Future date '{$date}' was not validated");
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('date', $e->errors());
            $this->assertEquals($letter->date, $letter->fresh()->date);
        } catch (\Exception $e) {
            $this->fail("Future date '{$date}' was not validated");
        }

        $date = now()->subMonth(1)->format('Y-m-d');
        $this->withoutExceptionHandling()
            ->patch("/outgoing-letter-logs/{$letter->id}", compact('date'))
            ->assertRedirect('/outgoing-letter-logs');

        $this->assertEquals($date, $letter->fresh()->date);
    }

    /** @test */
    public function request_validates_type_field_is_not_null()
    {
        $letter = factory(OutgoingLetterLog::class)->create();
        try {
            $this->be(factory(\App\User::class)->create());
        
            $this->withoutExceptionHandling()
                ->patch("/outgoing-letter-logs/{$letter->id}", ['type' => '']);
            
            $this->fail('Empty \'type\' field was not validated.');
        } catch(ValidationException $e) {
            $this->assertArrayHasKey('type', $e->errors());
            $this->assertEquals($letter->type, $letter->fresh()->type);
        }
    }

    /** @test */
    public function request_validates_recipient_field_is_not_null()
    {
        $letter = factory(OutgoingLetterLog::class)->create();
        try {
            $this->be(factory(\App\User::class)->create());
        
            $this->withoutExceptionHandling()
                ->patch("/outgoing-letter-logs/{$letter->id}", ['recipient' => '']);
            
            $this->fail('Empty \'recipient\' field was not validated.');
        } catch(ValidationException $e) {
            $this->assertArrayHasKey('recipient', $e->errors());
            $this->assertEquals($letter->recipient, $letter->fresh()->recipient);
        }
    }

    /** @test */
    public function request_validates_sender_id_field_is_not_null()
    {
        $letter = factory(OutgoingLetterLog::class)->create();
        try {
            $this->be(factory(\App\User::class)->create());
        
            $this->withoutExceptionHandling()
                ->patch("/outgoing-letter-logs/{$letter->id}", ['sender_id' => '']);
            
            $this->fail('Empty \'sender_id\' field was not validated.');
        } catch(ValidationException $e) {
            $this->assertArrayHasKey('sender_id', $e->errors());
            $this->assertEquals($letter->sender_id, $letter->fresh()->sender_id);
        }
    }

    /** @test */
    public function request_validates_sender_id_field_must_be_a_existing_user()
    {
        $letter = factory(OutgoingLetterLog::class)->create();
        try {
            $this->be(factory(\App\User::class)->create());
        
            $this->withoutExceptionHandling()
                ->patch("/outgoing-letter-logs/{$letter->id}", ['sender_id' => 4]);
            
            $this->fail('Failed to validate \'sender_id\' is a valid existing user id');
        } catch(ValidationException $e) {
            $this->assertArrayHasKey('sender_id', $e->errors());
            $this->assertEquals($letter->sender_id, $letter->fresh()->sender_id);
        }
    }

    /** @test */
    public function request_validates_description_field_can_be_null()
    {
        $letter = factory(OutgoingLetterLog::class)->create();
        $this->be(factory(\App\User::class)->create());
    
        $this->withoutExceptionHandling()
            ->patch("/outgoing-letter-logs/{$letter->id}", ['description' => ''])
            ->assertRedirect('/outgoing-letter-logs');

        $this->assertNull($letter->fresh()->description);
    }

    /** @test */
    public function request_validates_amount_field_can_be_null()
    {
        $letter = factory(OutgoingLetterLog::class)->create();
        $this->be(factory(\App\User::class)->create());
    
        $this->withoutExceptionHandling()
            ->patch("/outgoing-letter-logs/{$letter->id}", ['amount' => ''])
            ->assertRedirect('/outgoing-letter-logs');

        $this->assertNull($letter->fresh()->amount);
    }

    /** @test */
    public function request_validates_amount_field_can_be_a_string_value()
    {
        $letter = factory(OutgoingLetterLog::class)->create();
        try {
            $this->be(factory(\App\User::class)->create());
                
            $this->withoutExceptionHandling()
                ->patch("/outgoing-letter-logs/{$letter->id}", ['amount' => 'some string']);
                    
            $this->fail('Failed to validate \'amount\' cannot be a string value');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('amount', $e->errors());
            $this->assertEquals($letter->amount, $letter->fresh()->amount);
        }
    }
}
