<?php

namespace Tests\Feature;

use \App\User;
use Tests\TestCase;
use App\OutgoingLetter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\str;

class StoreOutgoingLettersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_store_outgoing_letters()
    {
        $this->withExceptionHandling()
            ->post('/outgoing-letters')
            ->assertRedirect('/login');
            
        $this->assertEquals(0, OutgoingLetter::count());
    }

    /** @test */
    public function store_outgoing_letter_in_database()
    {
        $this->be(factory(User::class)->create());
        
        $outgoing_letter = factory(OutgoingLetter::class)->make();

        $this->withoutExceptionHandling()
            ->post('/outgoing-letters', $outgoing_letter->toArray())
            ->assertRedirect('/outgoing-letters');
            
        $this->assertEquals(1, OutgoingLetter::count());
    }

    /** @test */
    public function request_validates_date_field_is_not_null()
    {
        try {
            $this->be(factory(User::class)->create());
            $letter = factory(OutgoingLetter::class)->make()->toArray();
            unset($letter['date']);

            $this->withoutExceptionHandling()
                ->post('/outgoing-letters', $letter);
            
            $this->fail('Empty date field was not validated.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('date', $e->errors());
            $this->assertEquals(0, OutgoingLetter::count());
        }
    }

    /** @test */
    public function request_validates_date_field_is_a_valid_date()
    {
        $this->be($user = factory(\App\User::class)->create());
        $letter = factory(\App\OutgoingLetter::class)->make()->toArray();

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
                    ->post('/outgoing-letters', $letter);
                $this->fail("Invalid date '{$date}' was not validated");
            } catch (ValidationException $e) {
                $this->assertArrayHasKey('date', $e->errors());
                $this->assertEquals(0, OutgoingLetter::count());
            } catch (\Exception $e) {
                $this->fail("Invalid date '{$date}' was not validated");
            }
        }

        foreach ($validDates as $date) {
            $letter['date'] = $date;
            $this->withoutExceptionHandling()
                ->post('/outgoing-letters', $letter)
                ->assertRedirect('/outgoing-letters');
            $this->assertEquals(1, OutgoingLetter::count());
            OutgoingLetter::truncate();
        }
    }

    /** @test */
    public function request_validates_date_field_cannot_be_a_future_date()
    {
        $this->be(factory(\App\User::class)->create());
        $letter = factory(OutgoingLetter::class)->make();
        try {
            $letter->date = now()->addMonth(1)->format('Y-m-d');

            $this->withoutExceptionHandling()
                ->post('/outgoing-letters', $letter->toArray());

            $this->fail("Future date '{$letter->date}' was not validated");
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('date', $e->errors());
            $this->assertEquals(0, OutgoingLetter::count());
        } catch (\Exception $e) {
            $this->fail("Future date '{$letter->date}' was not validated");
        }

        $letter->date = now()->subMonth(1)->format('Y-m-d');
        $this->withoutExceptionHandling()
            ->post('/outgoing-letters', $letter->toArray())
            ->assertRedirect('/outgoing-letters');

        $this->assertEquals(1, OutgoingLetter::count());
    }

    /** @test */
    public function request_validates_type_field_is_not_null()
    {
        try {
            $this->be(factory(\App\User::class)->create());
            $letter = factory(OutgoingLetter::class)->make(['type' => '']);
        
            $this->withoutExceptionHandling()
                ->post('/outgoing-letters', $letter->toArray());
            
            $this->fail('Empty \'type\' field was not validated.');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('type', $e->errors());
            $this->assertEquals(0, OutgoingLetter::count());
        } catch (\Exception $e) {
            // $this->fail('Empty \'type\' field was not validated.');
            $this->fail($e->getMessage());
        }
    }

    /** @test */
    public function request_validates_subject_field_is_not_null()
    {
        try {
            $this -> be(factory(\App\User::class)->create());
            $letter = factory(OutgoingLetter::class)->make(['subject' => '']);

            $this->withoutExceptionHandling()
                ->post('/outgoing-letters',$letter->toArray());

            $this->fail('Empty \'subject\' field was not validated.');
        }catch(ValidationException $e) {
            $this->assertArrayHasKey('subject',$e->errors());
        }

        $this->assertEquals(0,OutgoingLetter::count());
    }

    /** @test */
    public function request_validates_subject_field_maxlimit_80()
    {
        try
        {
            $this -> be(factory(\App\User::class)->create());
            $letter = factory(OutgoingLetter::class)->make(['subject' => Str::random(81)]);

            $this -> withoutExceptionHandling()
                -> post('/outgoing-letters',$letter -> toArray());
        }catch(ValidationException $e){
            $this -> assertArrayHasKey('subject',$e->errors());
        }

        $this -> assertEquals(0,OutgoingLetter::count());
    }

    /** @test */
    public function request_validates_recipient_field_is_not_null()
    {
        try {
            $this->be(factory(\App\User::class)->create());
            $letter = factory(OutgoingLetter::class)->make(['recipient' => '']);
        
            $this->withoutExceptionHandling()
                ->post('/outgoing-letters', $letter->toArray());
            
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
            $this->be(factory(\App\User::class)->create());
            $letter = factory(OutgoingLetter::class)->make(['sender_id' => '']);
        
            $this->withoutExceptionHandling()
                ->post('/outgoing-letters', $letter->toArray());
            
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
            $this->be(factory(\App\User::class)->create());
            $letter = factory(OutgoingLetter::class)->make(['sender_id' => 4]);
            
            $this->withoutExceptionHandling()
                ->post('/outgoing-letters', $letter->toArray());
            
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
        $this->be(factory(\App\User::class)->create());
        $letter = factory(OutgoingLetter::class)->make()->toArray();

        unset($letter['description']);
    
        $this->withoutExceptionHandling()
            ->post('/outgoing-letters', $letter)
            ->assertRedirect('/outgoing-letters');

        $this->assertEquals(1, OutgoingLetter::count());
    }

    /** @test */
    public function request_validates_amount_field_can_be_null()
    {
        $this->be(factory(\App\User::class)->create());
        $letter = factory(OutgoingLetter::class)->make(['amount' => '']);
    
        $this->withoutExceptionHandling()
            ->post('/outgoing-letters', $letter->toArray())
            ->assertRedirect('/outgoing-letters');

        $this->assertEquals(1, OutgoingLetter::count());
    }

    /** @test */
    public function request_validates_amount_field_cannot_be_a_string_value()
    {
        try {
            $this->be(factory(\App\User::class)->create());
            $letter = factory(OutgoingLetter::class)->make(['amount' => 'some string']);
                
            $this->withoutExceptionHandling()
                        ->post('/outgoing-letters', $letter->toArray());
                    
            $this->fail('Failed to validate \'amount\' cannot be a string value');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('amount', $e->errors());
            $this->assertEquals(0, OutgoingLetter::count());
        } catch (\Exception $e) {
            $this->fail('Failed to validate \'sender_id\' cannot be a string value');
        }
    }
}
