<?php

namespace Tests\Feature;

use App\IncomingLetter;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateIncomingLettersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_create_letters()
    {
        $this->withExceptionHandling()
            ->get(route('staff.incoming_letters.create'))
            ->assertRedirect('/login');
    }

    /** @test */
    public function user_can_create_letters()
    {
        $this->signIn();

        $this->withoutExceptionHandling()
            ->get(route('staff.incoming_letters.create'))
            ->assertSuccessful()
            ->assertViewIs('staff.incoming_letters.create');
    }
}
