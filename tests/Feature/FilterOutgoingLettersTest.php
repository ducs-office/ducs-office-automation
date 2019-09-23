<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\OutgoingLetter;
use Illuminate\Support\Collection;
use App\User;
use Illuminate\Support\Carbon;

class FilterOutgoingLettersTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function user_can_view_filtered_letters_based_on_before_given_date()
    {
        factory(OutgoingLetter::class)->create(['date' => '2017-08-09']);
        factory(OutgoingLetter::class)->create(['date' => '2017-08-15']);
        factory(OutgoingLetter::class)->create(['date' => '2017-10-12']);

        $beforeFilter = '2017-09-01';

        $this->be(factory(User::class)->create());

        $viewOutgoingLetters = $this->withoutExceptionHandling()
            ->get('/outgoing-letters?filters[date][less_than]=' . $beforeFilter)
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
            ->get('/outgoing-letters?filters[date][greater_than]=' . $afterFilter)
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
            ->get('/outgoing-letters?filters[date][greater_than]=' . $afterFilter . '&filters[date][less_than]='. $beforeFilter)
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

    /** @test */
    public function user_can_view_filtered_letters_even_if_after_date_not_given()
    {
        factory(OutgoingLetter::class)->create(['date' => '1997-07-15']);
        $this -> be(factory(User::class)->create());
        $after_date = '';

        $viewLetters = $this -> withoutExceptionHandling()
            ->get('/outgoing-letters?filters[date][greater_than]='.$after_date)
            ->assertSuccessful()
            ->assertViewIs('outgoing_letters.index')
            ->assertViewHas('outgoing_letters')
            ->viewData('outgoing_letters');

        $this -> assertCount(1,$viewLetters);
    }

    /** @test */
    public function user_can_view_filtered_letters_even_if_before_date_not_given()
    {
        factory(OutgoingLetter::class)->create(['date' => '1997-07-15']);
        $this -> be(factory(User::class)->create());
        $before_date = '';

        $viewLetters = $this -> withoutExceptionHandling()
            ->get('/outgoing-letters?before='.$before_date)
            ->assertSuccessful()
            ->assertViewIs('outgoing_letters.index')
            ->assertViewHas('outgoing_letters')
            ->viewData('outgoing_letters');

        $this -> assertCount(1,$viewLetters);
    }

    /** @test */
    public function user_can_view_filtered_letters_even_if_before_and_after_date_not_given()
    {
        factory(OutgoingLetter::class)->create(['date' => '1997-07-15']);
        $this -> be(factory(User::class)->create());
        $after_date = '';
        $before_date = '';

        $viewLetters = $this -> withoutExceptionHandling()
            ->get('/outgoing-letters?filters[date][greater_than]='.$after_date.'&filters[date][less_than]='.$before_date)
            ->assertSuccessful()
            ->assertViewIs('outgoing_letters.index')
            ->assertViewHas('outgoing_letters')
            ->viewData('outgoing_letters');

        $this -> assertCount(1,$viewLetters);
    }

    /** @test */
    public function user_can_filter_letters_based_on_its_type()
    {
        $bills = factory(OutgoingLetter::class, 2)->create(['type' => 'Bill']);
        factory(OutgoingLetter::class)->create(['type' => 'Invitation Letter']);
        factory(OutgoingLetter::class)->create(['type' => 'Fellowship']);
        $this -> be(factory(User::class)->create());

        $viewLetters = $this -> withoutExceptionHandling()
            ->get('/outgoing-letters?filters[type][equals]=Bill')
            ->assertSuccessful()
            ->assertViewIs('outgoing_letters.index')
            ->assertViewHas('outgoing_letters')
            ->viewData('outgoing_letters');

        $this->assertCount(2,$viewLetters);
        $this->assertEquals($bills[0]->id, $viewLetters[0]->id);
        $this->assertEquals($bills[1]->id, $viewLetters[1]->id);
    }

    /** @test */
    public function user_can_filter_letters_based_on_recipient()
    {
        $sentToDUCC = factory(OutgoingLetter::class, 2)->create(['recipient' => 'DUCC']);
        factory(OutgoingLetter::class)->create(['recipient' => 'Director, DU']);
        factory(OutgoingLetter::class)->create(['recipient' => 'Examination Center']);

        $this->be(factory(User::class)->create());

        $viewLetters = $this -> withoutExceptionHandling()
            ->get('/outgoing-letters?filters[recipient][equals]=DUCC')
            ->assertSuccessful()
            ->assertViewIs('outgoing_letters.index')
            ->assertViewHas('outgoing_letters')
            ->viewData('outgoing_letters');

        $this->assertCount(2,$viewLetters);
        $this->assertEquals($sentToDUCC[0]->id, $viewLetters[0]->id);
        $this->assertEquals($sentToDUCC[1]->id, $viewLetters[1]->id);
    }

    /** @test */
    public function user_can_filter_letters_based_on_subject()
    {
        $subject1 = factory(OutgoingLetter::class)->create(['subject' => 'Invitation for workshop']);
        $subject2 = factory(OutgoingLetter::class)->create(['subject' => 'Invitation for event']);
        $subject3 = factory(OutgoingLetter::class)->create(['subject' => 'Request for extending deadline']);

        $this->be(factory(User::class)->create());

        $viewLetters = $this -> withoutExceptionHandling()
            ->get('/outgoing-letters?filters[subject][like]=%invitation%')
            ->assertSuccessful()
            ->assertViewIs('outgoing_letters.index')
            ->assertViewHas('outgoing_letters')
            ->viewData('outgoing_letters');

        $this->assertCount(2,$viewLetters);
        $this->assertEquals($subject1->id, $viewLetters[0]->id);
        $this->assertEquals($subject2->id, $viewLetters[1]->id);
    }
}
