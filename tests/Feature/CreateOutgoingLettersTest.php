<?php

namespace Tests\Feature;

use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\UnauthorizedException;

class CreateOutgoingLettersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_fill_outgoing_letters_form()
    {
        $this->be(factory(\App\User::class)->create());

        $this->withoutExceptionHandling()
            ->get('/outgoing-letters/create')
            ->assertSuccessful()
            ->assertViewIs('outgoing_letters.create');
    }

    /** @test */
    public function guest_cannot_fill_outgoing_letters_form()
    {
        $this->expectException(AuthenticationException::class);

        $this->withoutExceptionHandling()
            ->get('/outgoing-letters/create');
    }
}
