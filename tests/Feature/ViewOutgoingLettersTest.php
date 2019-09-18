<?php

namespace Tests\Feature;

use App\OutgoingLetter;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ViewOutgoingLettersTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function guest_cannot_view_outgoing_letters()
    {
        $this->get('/outgoing-letters')
            ->assertRedirect('/login');
    }

    /** @test */
    public function user_can_view_outgoing_letters()
    {
        $this->be(factory(User::class)->create());
        factory(OutgoingLetter::class, 3)->create();

        $viewOutgoingLetters = $this->withoutExceptionHandling()
            ->get('/outgoing-letters')
            ->assertSuccessful()
            ->assertViewIs('outgoing_letters.index')
            ->assertViewHas('outgoing_letters')
            ->viewData('outgoing_letters');

        $this->assertInstanceOf(Collection::class, $viewOutgoingLetters);
        $this->assertCount(3, $viewOutgoingLetters);
    }
}
