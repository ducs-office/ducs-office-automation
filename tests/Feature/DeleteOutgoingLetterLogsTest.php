<?php

namespace Tests\Feature;

use App\OutgoingLetterLog;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteOutgoingLetterLogsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_delete_letter_logs()
    {
        $this -> delete('/outgoing-letter-logs?letterid = 1')
              -> assertRedirect('/login');  
    }

    /** @test */
    public function user_can_delete_letter_logs()
    {
        $this -> be(factory(User::class)->create());
        $letter = factory(OutgoingLetterLog::class)->create();

        $this -> withoutExceptionHandling()
              -> delete('/outgoing-letter-logs?letter_id='.$letter['id'])  
              -> assertViewIs('outgoing_letter_logs.index');

        $this -> assertEquals(0,OutgoingLetterLog::count());
    }
    
}
