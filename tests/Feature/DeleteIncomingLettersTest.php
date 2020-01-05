<?php

namespace Tests\Feature;

use App\User;
use App\IncomingLetter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteIncomingLettersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_delete_letters()
    {
        $letter = create(IncomingLetter::class);

        $this->withExceptionHandling()
            ->delete("/incoming-letters/$letter->id")
            ->assertRedirect('/login');
        
        $this->assertEquals(IncomingLetter::count(), 1);
    }

    /** @test */
    public function user_can_delete_letters()
    {
        $this->signIn();

        $letter = create(IncomingLetter::class, 1, ['creator_id' => auth()->id()]);

        $this->withoutExceptionHandling()
         ->delete("/incoming-letters/$letter->id")
         ->assertRedirect('/incoming-letters');
         
        $this->assertEquals(IncomingLetter::count(), 0);
    }
}
