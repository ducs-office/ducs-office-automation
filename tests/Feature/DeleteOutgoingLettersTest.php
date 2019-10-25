<?php

namespace Tests\Feature;

use App\OutgoingLetter;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteOutgoingLettersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_delete_letters()
    {
        $letter = create(OutgoingLetter::class);

        $this -> delete("/outgoing-letters/$letter->id")
              -> assertRedirect('/login');

        $this -> assertEquals(1,OutgoingLetter::count());
    }

    /** @test */
    public function user_can_delete_letters()
    {
        $this -> be(create(User::class));
        $letter = create(OutgoingLetter::class);

        $this -> withoutExceptionHandling()
              -> delete("/outgoing-letters/$letter->id")
              -> assertRedirect('/outgoing-letters');

        $this -> assertEquals(0,OutgoingLetter::count());
    }

}
