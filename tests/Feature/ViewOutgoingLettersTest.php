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

    /** @test */
    public function view_letters_are_sorted_on_date() 
    {
        $this->be(factory(User::class)->create());
        $letters = factory(OutgoingLetter::class,3)->create();

        $viewData = $this->withExceptionHandling()
            ->get('/outgoing-letters')
            ->assertSuccessful()
            ->assertViewIs('outgoing_letters.index')
            ->assertViewHas('outgoing_letters')
            ->viewData('outgoing_letters');
        
        $letters = $letters->sortByDesc('date');
        $sorted_letters_ids = $letters->pluck('id')->toArray();
        $viewData_ids = $viewData->pluck('id')->toArray();
        $this->assertSame($sorted_letters_ids, $viewData_ids); 
    }

    /** @test */

    public function view_has_a_unique_list_of_recipients()
    {
        $this->be(factory(User::class)->create());

        factory(OutgoingLetter::class,3)->create(['recipient' => 'Exam Office']);
        factory(OutgoingLetter::class)->create();
        factory(OutgoingLetter::class)->create();

        $recipients = $this->withExceptionHandling()
                ->get('/outgoing-letters')
                ->assertSuccessful()
                ->assertViewIs('outgoing_letters.index')
                ->assertViewHas('recipients')
                ->viewData('recipients');
        
        $this->assertCount(3,$recipients);
        $this->assertSame(
            OutgoingLetter::all()->pluck('recipient', 'recipient')->toArray(),
            $recipients->toArray()
        );
    }

    /** @test */
    public function view_has_a_unique_list_of_types()
    {
        $this->be(factory(User::class)->create());

        factory(OutgoingLetter::class,3)->create(['type' => 'Invite Letter']);
        factory(OutgoingLetter::class)->create();
        factory(OutgoingLetter::class)->create();

        $types = $this->withExceptionHandling()
                ->get('/outgoing-letters')
                ->assertSuccessful()
                ->assertViewIs('outgoing_letters.index')
                ->assertViewHas('types')
                ->viewData('types');
        
        $this->assertCount(3,$types);
        $this->assertSame(
            OutgoingLetter::all()->pluck('type', 'type')->toArray(), 
            $types->toArray()
        );
    }

    /** @test */
    public function view_has_a_unique_list_of_senders()
    {
        $this->be(factory(User::class)->create());

        factory(OutgoingLetter::class,3)->create(['sender_id' => 2]);
        factory(OutgoingLetter::class)->create();
        factory(OutgoingLetter::class)->create();

        $senders = $this->withExceptionHandling()
                ->get('/outgoing-letters')
                ->assertSuccessful()
                ->assertViewIs('outgoing_letters.index')
                ->assertViewHas('senders')
                ->viewData('senders');
        
        $this->assertCount(3,$senders);
        $this->assertSame(
            OutgoingLetter::with('sender')->get()->pluck('sender.name', 'sender_id')->toArray(), 
            $senders->toArray()
        );
    }

    /** @test */
    public function view_has_a_unique_list_of_creators()
    {
        $this->be($user = factory(User::class)->create());

        factory(OutgoingLetter::class,3)->create(['creator_id' => $user->id]);
        factory(OutgoingLetter::class)->create();
        factory(OutgoingLetter::class)->create();

        $creators = $this->withExceptionHandling()
                ->get('/outgoing-letters')
                ->assertSuccessful()
                ->assertViewIs('outgoing_letters.index')
                ->assertViewHas('creators')
                ->viewData('creators');
        
        $this->assertCount(3,$creators);
        $this->assertSame(
            OutgoingLetter::with('creator')->get()->pluck('creator.name', 'creator_id')->toArray(), 
            $creators->toArray()
        );
    }
}
