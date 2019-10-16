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
        create(OutgoingLetter::class, 1, ['date' => '2017-08-09']);
        create(OutgoingLetter::class, 1, ['date' => '2017-08-15']);
        create(OutgoingLetter::class, 1, ['date' => '2017-10-12']);

        $beforeFilter = '2017-09-01';

        $this->signIn();

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
        create(OutgoingLetter::class, 1, ['date' => '2017-08-09']);
        create(OutgoingLetter::class, 1, ['date' => '2017-08-15']);
        create(OutgoingLetter::class, 1, ['date' => '2017-10-12']);

        $afterFilter = '2017-09-01';

        $this->signIn();

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
        create(OutgoingLetter::class, 1, ['date' => '2017-07-20']);
        create(OutgoingLetter::class, 1, ['date' => '2017-08-09']);
        create(OutgoingLetter::class, 1, ['date' => '2017-08-15']);
        create(OutgoingLetter::class, 1, ['date' => '2017-10-12']);

        $afterFilter = '2017-08-01';
        $beforeFilter = '2017-09-01';

        $this->signIn();

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
        create(OutgoingLetter::class, 1, ['date' => '1997-07-15']);
        $this -> be(create(User::class));
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
        create(OutgoingLetter::class, 1, ['date' => '1997-07-15']);
        $this -> be(create(User::class));
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
        create(OutgoingLetter::class, 1, ['date' => '1997-07-15']);
        $this -> be(create(User::class));
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
        $bills = create(OutgoingLetter::class, 2, ['type' => 'Bill']);
        create(OutgoingLetter::class, 1, ['type' => 'Invitation Letter']);
        create(OutgoingLetter::class, 1, ['type' => 'Fellowship']);
        $this -> be(create(User::class));

        $viewLetters = $this -> withoutExceptionHandling()
            ->get('/outgoing-letters?filters[type][equals]=Bill')
            ->assertSuccessful()
            ->assertViewIs('outgoing_letters.index')
            ->assertViewHas('outgoing_letters')
            ->viewData('outgoing_letters');

        $this->assertCount(2,$viewLetters);
        $this->assertTrue($viewLetters->pluck('id')->contains($bills[0]->id));
        $this->assertTrue($viewLetters->pluck('id')->contains($bills[1]->id));
    }

    /** @test */
    public function user_can_filter_letters_based_on_recipient()
    {
        $sentToDUCC = create(OutgoingLetter::class, 2, ['recipient' => 'DUCC']);
        create(OutgoingLetter::class, 1, ['recipient' => 'Director, DU']);
        create(OutgoingLetter::class, 1, ['recipient' => 'Examination Center']);

        $this->signIn();

        $viewLetters = $this -> withoutExceptionHandling()
            ->get('/outgoing-letters?filters[recipient][equals]=DUCC')
            ->assertSuccessful()
            ->assertViewIs('outgoing_letters.index')
            ->assertViewHas('outgoing_letters')
            ->viewData('outgoing_letters');

        $this->assertCount(2,$viewLetters);
        $this->assertTrue($viewLetters->pluck('id')->contains($sentToDUCC[0]->id));
        $this->assertTrue($viewLetters->pluck('id')->contains($sentToDUCC[1]->id));
    }

    /** @test */
    public function user_can_filter_letters_based_on_creator()
    {
        $createdBy1 = create(OutgoingLetter::class, 2, ['creator_id' => 1]);
        create(OutgoingLetter::class, 1, ['creator_id' => 2]);
        create(OutgoingLetter::class, 1, ['creator_id' => 3]);

        $this->signIn();

        $viewLetters = $this -> withoutExceptionHandling()
            ->get('/outgoing-letters?filters[creator_id][equals]=1')
            ->assertSuccessful()
            ->assertViewIs('outgoing_letters.index')
            ->assertViewHas('outgoing_letters')
            ->viewData('outgoing_letters');

        $this->assertCount(2,$viewLetters);
        $this->assertTrue($viewLetters->pluck('id')->contains($createdBy1[0]->id));
        $this->assertTrue($viewLetters->pluck('id')->contains($createdBy1[1]->id));
    }

    /** @test */
    public function user_can_filter_letters_based_on_sender()
    {
        $sentBy1 = create(OutgoingLetter::class, 2, ['sender_id' => 1]);
        create(OutgoingLetter::class, 1, ['sender_id' => 2]);
        create(OutgoingLetter::class, 1, ['sender_id' => 3]);

        $this->signIn();

        $viewLetters = $this -> withoutExceptionHandling()
            ->get('/outgoing-letters?filters[sender_id][equals]=1')
            ->assertSuccessful()
            ->assertViewIs('outgoing_letters.index')
            ->assertViewHas('outgoing_letters')
            ->viewData('outgoing_letters');

        $this->assertCount(2,$viewLetters);
        $this->assertTrue($viewLetters->pluck('id')->contains($sentBy1[0]->id));
        $this->assertTrue($viewLetters->pluck('id')->contains($sentBy1[1]->id));
    }

    /** @test */
    public function user_can_search_letters_based_on_subject_and_description ()
    {
        $sender1 = create(OutgoingLetter::class, 1, ['subject' => 'abc def ghi' , 'description' => 'abc jkl ghi']);
        $sender2 = create(OutgoingLetter::class, 1, ['subject'=>'jkl abc ghi' , 'description'=>'ghi def jkl']);
        create(OutgoingLetter::class, 1, ['subject'=>'abc ghi jkl' , 'description'=>'ghi abc jkl xyz']);

        $this->signIn();

        $viewLetters = $this->withoutExceptionHandling()
                            ->get('/outgoing-letters?search=def')
                            ->assertSuccessful()
                            ->assertViewIs('outgoing_letters.index')
                            ->assertViewHas('outgoing_letters')
                            ->viewData('outgoing_letters');

        $this->assertCount(2,$viewLetters);
        $this->assertTrue($viewLetters->pluck('id')->contains($sender1->id));
        $this->assertTrue($viewLetters->pluck('id')->contains($sender2->id));
    }
}
