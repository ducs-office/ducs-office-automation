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
            ->get('/incoming-letters/create')
            ->assertRedirect('/login');
    }

    /** @test */
    public function user_can_create_letters()
    {
        $this->signIn();

        $this->withoutExceptionHandling()
            ->get('/incoming-letters/create')
            ->assertSuccessful()
            ->assertViewIs('incoming_letters.create');
    }
}
