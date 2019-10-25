<?php

namespace Tests\Feature;

use App\OutgoingLetter;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditOutgoingLettersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_edit_outgoing_letters()
    {
        $this->signIn();
        $letter = create(OutgoingLetter::class);

        $this->withoutExceptionHandling()
            ->get("/outgoing-letters/$letter->id/edit")
            ->assertSuccessful()
            ->assertViewIs('outgoing_letters.edit')
            ->assertViewHas('outgoing_letter');
    }

    /** @test */
    public function guest_cannot_edit_any_outgoing_letter()
    {
        $this->expectException(AuthenticationException::class);

        $letter = create(OutgoingLetter::class);

        $this->withoutExceptionHandling()
            ->get("/outgoing-letters/$letter->id/edit");
    }
}
