<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\OutgoingLetterLog;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

}
