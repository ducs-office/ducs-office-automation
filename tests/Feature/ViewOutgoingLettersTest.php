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
    public function user_can_view_filtered_letters_based_on_before_given_date()
    {
        factory(OutgoingLetter::class)->create(['date' => '2017-08-09']);
        factory(OutgoingLetter::class)->create(['date' => '2017-08-15']);
        factory(OutgoingLetter::class)->create(['date' => '2017-10-12']);

        $beforeFilter = '2017-09-01';

        $this->be(factory(User::class)->create());

        $viewOutgoingLetters = $this->withoutExceptionHandling()
            ->get('/outgoing-letters?before=' . $beforeFilter)
            ->assertSuccessful()
            ->assertViewIs('outgoing_letters.index')
            ->assertViewHas('outgoing_letters')
            ->viewData('outgoing_letters');

        $this->assertInstanceOf(Collection::class, $viewOutgoingLetters);
        $this->assertCount(2, $viewOutgoingLetters, 'Only 2 letters were expected but :actual letters were returned');

        $lettersAfterBeforeFilter = $viewOutgoingLetters->filter(function($letter) use ($beforeFilter){
            return Carbon::parse($beforeFilter)->lessThan($letter->date);
        });
        
        $this->assertCount(0, $lettersAfterBeforeFilter, 'filtered logs do not respect `before` filter');
    }

    /** @test */
    public function user_can_view_filtered_letters_based_on_after_given_date()
    {
        factory(OutgoingLetter::class)->create(['date' => '2017-08-09']);
        factory(OutgoingLetter::class)->create(['date' => '2017-08-15']);
        factory(OutgoingLetter::class)->create(['date' => '2017-10-12']);

        $afterFilter = '2017-09-01';
        
        $this->be(factory(User::class)->create());
        
        $viewOutgoingLetters = $this->withoutExceptionHandling()
            ->get('/outgoing-letters?after=' . $afterFilter)
            ->assertSuccessful()
            ->assertViewIs('outgoing_letters.index')
            ->assertViewHas('outgoing_letters')
            ->viewData('outgoing_letters');

        $this->assertInstanceOf(Collection::class, $viewOutgoingLetters);
        $this->assertCount(1, $viewOutgoingLetters, 'Only 1 letter was expected but :actual letters were returned');

        $lettersBeforeAfterFilter = $viewOutgoingLetters->filter(function($letter) use ($afterFilter){
            return Carbon::parse($afterFilter)->greaterThan($letter->date);
        });
        
        $this->assertCount(0, $lettersBeforeAfterFilter, 'filtered letters do not respect `after` filter');
    }

    /** @test */
    public function user_can_view_filtered_letters_based_on_before_and_after_given_date()
    {
        factory(OutgoingLetter::class)->create(['date' => '2017-07-20']);
        factory(OutgoingLetter::class)->create(['date' => '2017-08-09']);
        factory(OutgoingLetter::class)->create(['date' => '2017-08-15']);
        factory(OutgoingLetter::class)->create(['date' => '2017-10-12']);

        $afterFilter = '2017-08-01';
        $beforeFilter = '2017-09-01';
        
        $this->be(factory(User::class)->create());
        
        $viewOutgoingLetters = $this->withoutExceptionHandling()
            ->get('/outgoing-letters?after=' . $afterFilter . '&before='. $beforeFilter)
            ->assertSuccessful()
            ->assertViewIs('outgoing_letters.index')
            ->assertViewHas('outgoing_letters')
            ->viewData('outgoing_letters');

        $this->assertInstanceOf(Collection::class, $viewOutgoingLetters);
        $this->assertCount(2, $viewOutgoingLetters, 'Only 2 letters were expected but :actual letters were returned');

        $lettersOutOfFilterRange = $viewOutgoingLetters->filter(function($letter) use ($afterFilter, $beforeFilter){
            return Carbon::parse($beforeFilter)->lessThan($letter->date)
                && Carbon::parse($afterFilter)->greaterThan($letter->date);
        });
        
        $this->assertCount(0, $lettersOutOfFilterRange, 'filtered letters do not respect `before` and `after` filter');
    }
}
