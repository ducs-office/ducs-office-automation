<?php

namespace Tests\Feature;

use App\IncomingLetter;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EditIncomingLettersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_not_edit_any_incoming_letter()
    {
        $this->expectException(AuthenticationException::class);

        $letter= create(IncomingLetter::class);

        $this->withoutExceptionHandling()
            ->get("incoming-letters/$letter->id/edit")
            ->assertRedirect('/login');
    }

    /** @test */
    public function user_can_edit_incoming_letters()
    {
        $this->signIn();
        $letter = create(IncomingLetter::class);

        $this->withExceptionHandling()
            ->get("/incoming-letters/$letter->id/edit")
            ->assertSuccessful()
            ->assertViewIs('incoming_letters.edit')
            ->assertViewHas('incoming_letter');
    }
}
