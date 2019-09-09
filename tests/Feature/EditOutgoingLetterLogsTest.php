<?php

namespace Tests\Feature;

use App\OutgoingLetterLog;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditOutgoingLetterLogsTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function user_can_edit_letter_logs()
    {
        $this->be(factory(User::class)->create());
        $letter = factory(OutgoingLetterLog::class)->create();

        $this->withoutExceptionHandling()
            ->get("/outgoing-letter-logs/$letter->id")
            ->assertSuccessful()
            ->assertViewIs('outgoing_letter_logs.edit')
            ->assertViewHas('outgoing_letter', $letter);
    }

    /** @test */
    public function guest_cannot_edit_any_letter_log()
    {
        $this->expectException(AuthenticationException::class);
        
        $letter = factory(OutgoingLetterLog::class)->create();
        
        $this->withoutExceptionHandling()
            ->get("/outgoing-letter-logs/$letter->id");
    }
}
