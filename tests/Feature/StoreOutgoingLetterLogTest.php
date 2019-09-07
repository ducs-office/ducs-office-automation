<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\OutgoingLetterLog;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreOutgoingLetterLogTest extends TestCase
{
    use RefreshDatabase;

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
}
