<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\OutgoingLetterLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

class StoreOutgoingLetterLogTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_store_letter_logs()
    {
        $this->withExceptionHandling()
            ->post('/outgoing-letter-logs')
            ->assertRedirect('/login');
            
        $this->assertEquals(0, OutgoingLetterLog::count());
    }

    /** @test */
    public function store_outgoing_letter_log_in_database()
    {
        $this->be(factory(\App\User::class)->create());
        
        $outgoing_letter_log = factory(OutgoingLetterLog::class)->make();

        $this->withoutExceptionHandling()
            ->post('/outgoing-letter-logs', $outgoing_letter_log->toArray())
            ->assertRedirect('/outgoing-letter-logs');
            
        $this->assertEquals(1, OutgoingLetterLog::count());
    }

    /** @test */
    public function request_validates_date_field_is_not_null()
    {
        try {
            $this->be(factory(\App\User::class)->create());
            $letter = factory(OutgoingLetterLog::class)->make(['date' => '']);
        
            $this->withoutExceptionHandling()
                ->post('/outgoing-letter-logs', $letter->toArray());
            
            $this->fail('Empty date field was not validated.');
        } catch(ValidationException $e) {
            $this->assertArrayHasKey('date', $e->errors());
            $this->assertEquals(0, OutgoingLetterLog::count());
        } catch(\Exception $e) {
            $this->fail('Empty date field was not validated.');
        }
    }

    /** @test */
    public function request_validates_date_field_is_a_valid_date()
    {
        $this->be(factory(\App\User::class)->create());
        $letter = factory(OutgoingLetterLog::class)->make();

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
                $letter->date = $date;
                    
                $this->withoutExceptionHandling()
                    ->post('/outgoing-letter-logs', $letter->toArray());
                        
                $this->fail("Invalid date '{$letter->date}' was not validated");
            } catch (ValidationException $e) {
                $this->assertArrayHasKey('date', $e->errors());
                $this->assertEquals(0, OutgoingLetterLog::count());
            } catch (\Exception $e) {
                $this->fail("Invalid date '{$letter->date}' was not validated");
            }
        }

        foreach( $validDates as $date) {
            $letter->date = $date;
            $this->withoutExceptionHandling()
                ->post('/outgoing-letter-logs', $letter->toArray())
                ->assertRedirect('/outgoing-letter-logs');

            $this->assertEquals(1, OutgoingLetterLog::count());
            OutgoingLetterLog::truncate();
        }
    }

    /** @test */
    public function request_validates_date_field_cannot_be_a_future_date()
    {
        $this->be(factory(\App\User::class)->create());
        $letter = factory(OutgoingLetterLog::class)->make();
        try {
            $letter->date = now()->addMonth(1)->format('Y-m-d');

            $this->withoutExceptionHandling()
                ->post('/outgoing-letter-logs', $letter->toArray());

            $this->fail("Future date '{$letter->date}' was not validated");
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('date', $e->errors());
            $this->assertEquals(0, OutgoingLetterLog::count());
        } catch (\Exception $e) {
            $this->fail("Future date '{$letter->date}' was not validated");
        }

        $letter->date = now()->subMonth(1)->format('Y-m-d');
        $this->withoutExceptionHandling()
            ->post('/outgoing-letter-logs', $letter->toArray())
            ->assertRedirect('/outgoing-letter-logs');

        $this->assertEquals(1, OutgoingLetterLog::count());
    }

    /** @test */
    public function request_validates_type_field_is_not_null()
    {
        try {
            $this->be(factory(\App\User::class)->create());
            $letter = factory(OutgoingLetterLog::class)->make(['type' => '']);
        
            $this->withoutExceptionHandling()
                ->post('/outgoing-letter-logs', $letter->toArray());
            
            $this->fail('Empty \'type\' field was not validated.');
        } catch(ValidationException $e) {
            $this->assertArrayHasKey('type', $e->errors());
            $this->assertEquals(0, OutgoingLetterLog::count());
        } catch(\Exception $e) {
            $this->fail('Empty \'type\' field was not validated.');
        }
    }

    /** @test */
    public function request_validates_recipient_field_is_not_null()
    {
        try {
            $this->be(factory(\App\User::class)->create());
            $letter = factory(OutgoingLetterLog::class)->make(['recipient' => '']);
        
            $this->withoutExceptionHandling()
                ->post('/outgoing-letter-logs', $letter->toArray());
            
            $this->fail('Empty \'recipient\' field was not validated.');
        } catch(ValidationException $e) {
            $this->assertArrayHasKey('recipient', $e->errors());
            $this->assertEquals(0, OutgoingLetterLog::count());
        } catch(\Exception $e) {
            $this->fail('Empty \'recipient\' field was not validated.');
        }
    }

    /** @test */
    public function request_validates_sender_id_field_is_not_null()
    {
        try {
            $this->be(factory(\App\User::class)->create());
            $letter = factory(OutgoingLetterLog::class)->make(['sender_id' => '']);
        
            $this->withoutExceptionHandling()
                ->post('/outgoing-letter-logs', $letter->toArray());
            
            $this->fail('Empty \'sender_id\' field was not validated.');
        } catch(ValidationException $e) {
            $this->assertArrayHasKey('sender_id', $e->errors());
            $this->assertEquals(0, OutgoingLetterLog::count());
        } catch(\Exception $e) {
            $this->fail('Empty \'sender_id\' field was not validated.');
        }
    }

    /** @test */
    public function request_validates_sender_id_field_must_be_a_existing_user()
    {
        try {
            $this->be(factory(\App\User::class)->create());
            $letter = factory(OutgoingLetterLog::class)->make(['sender_id' => 4]);
        
            $this->withoutExceptionHandling()
                ->post('/outgoing-letter-logs', $letter->toArray());
            
            $this->fail('Failed to validate \'sender_id\' is a valid existing user id');
        } catch(ValidationException $e) {
            $this->assertArrayHasKey('sender_id', $e->errors());
            $this->assertEquals(0, OutgoingLetterLog::count());
        } catch(\Exception $e) {
            $this->fail('Failed to validate \'sender_id\' is a valid existing user id');
        }
    }

    /** @test */
    public function request_validates_description_field_can_be_null()
    {
        $this->be(factory(\App\User::class)->create());
        $letter = factory(OutgoingLetterLog::class)->make(['description' => '']);
    
        $this->withoutExceptionHandling()
            ->post('/outgoing-letter-logs', $letter->toArray())
            ->assertRedirect('/outgoing-letter-logs');

        $this->assertEquals(1, OutgoingLetterLog::count());
    }

    /** @test */
    public function request_validates_amount_field_can_be_null()
    {
        $this->be(factory(\App\User::class)->create());
        $letter = factory(OutgoingLetterLog::class)->make(['amount' => '']);
    
        $this->withoutExceptionHandling()
            ->post('/outgoing-letter-logs', $letter->toArray())
            ->assertRedirect('/outgoing-letter-logs');

        $this->assertEquals(1, OutgoingLetterLog::count());
    }

    /** @test */
    public function request_validates_amount_field_can_be_a_string_value()
    {
        try {
            $this->be(factory(\App\User::class)->create());
            $letter = factory(OutgoingLetterLog::class)->make(['amount' => 'some string']);
                
            $this->withoutExceptionHandling()
                        ->post('/outgoing-letter-logs', $letter->toArray());
                    
            $this->fail('Failed to validate \'amount\' cannot be a string value');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('amount', $e->errors());
            $this->assertEquals(0, OutgoingLetterLog::count());
        } catch (\Exception $e) {
            $this->fail('Failed to validate \'sender_id\' cannot be a string value');
        }
    }
}
