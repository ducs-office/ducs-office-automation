<?php

namespace Tests\Feature;

use App\IncomingLetter;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Tests\TestCase;

class FilterAndSearchIncomingLettersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_view_filtered_letters_based_on_before_given_date()
    {
        create(IncomingLetter::class, 1, ['date' => '2017-08-09']);
        create(IncomingLetter::class, 1, ['date' => '2017-08-15']);
        create(IncomingLetter::class, 1, ['date' => '2017-10-12']);

        $beforeFilter = '2017-09-01';

        $this->signIn();

        $viewIncomingLetters = $this->withoutExceptionHandling()
                                ->get('/incoming-letters?filters[date][less_than]='. $beforeFilter)
                                ->assertSuccessful()
                                ->assertViewIs('incoming_letters.index')
                                ->assertViewHas('incoming_letters')
                                ->viewdata('incoming_letters');

        $this->assertInstanceOf(Collection::class, $viewIncomingLetters);
        $this->assertCount(2, $viewIncomingLetters, 'Only 2 letters were expected but :actual letters were returned');

        $lettersAfterBeforeFilter = $viewIncomingLetters->filter(function ($letter) use ($beforeFilter) {
            return Carbon::parse($beforeFilter)->lessThan($letter->date);
        });

        $this->assertCount(0, $lettersAfterBeforeFilter, 'filtered letters do not respect `before` filter');
    }

    /** @test */
    public function user_can_view_filtered_letters_based_on_after_given_date()
    {
        create(IncomingLetter::class, 1, ['date' => '2017-08-09']);
        create(IncomingLetter::class, 1, ['date' => '2017-08-15']);
        create(IncomingLetter::class, 1, ['date' => '2017-10-12']);

        $afterFilter = '2017-09-01';

        $this->signIn();

        $viewIncomingLetters = $this->withoutExceptionHandling()
            ->get('/incoming-letters?filters[date][greater_than]=' . $afterFilter)
            ->assertSuccessful()
            ->assertViewIs('incoming_letters.index')
            ->assertViewHas('incoming_letters')
            ->viewData('incoming_letters');

        $this->assertInstanceOf(Collection::class, $viewIncomingLetters);
        $this->assertCount(1, $viewIncomingLetters, 'Only 1 letter was expected but :actual letters were returned');

        $lettersBeforeAfterFilter = $viewIncomingLetters->filter(function ($letter) use ($afterFilter) {
            return Carbon::parse($afterFilter)->greaterThan($letter->date);
        });

        $this->assertCount(0, $lettersBeforeAfterFilter, 'filtered letters do not respect `after` filter');
    }

    /** @test */
    public function user_can_view_filtered_letters_based_on_before_and_after_given_date()
    {
        create(IncomingLetter::class, 1, ['date' => '2017-07-20']);
        create(IncomingLetter::class, 1, ['date' => '2017-08-09']);
        create(IncomingLetter::class, 1, ['date' => '2017-08-15']);
        create(IncomingLetter::class, 1, ['date' => '2017-10-12']);

        $afterFilter = '2017-08-01';
        $beforeFilter = '2017-09-01';

        $this->signIn();

        $viewIncomingLetters = $this->withoutExceptionHandling()
            ->get('/incoming-letters?filters[date][greater_than]=' . $afterFilter . '&filters[date][less_than]='. $beforeFilter)
            ->assertSuccessful()
            ->assertViewIs('incoming_letters.index')
            ->assertViewHas('incoming_letters')
            ->viewData('incoming_letters');

        $this->assertInstanceOf(Collection::class, $viewIncomingLetters);
        $this->assertCount(2, $viewIncomingLetters, 'Only 2 letters were expected but :actual letters were returned');

        $lettersOutOfFilterRange = $viewIncomingLetters->filter(function ($letter) use ($afterFilter, $beforeFilter) {
            return Carbon::parse($beforeFilter)->lessThan($letter->date)
                && Carbon::parse($afterFilter)->greaterThan($letter->date);
        });

        $this->assertCount(0, $lettersOutOfFilterRange, 'filtered letters do not respect `before` and `after` filter');
    }

    /** @test */
    public function user_can_view_filtered_letters_even_if_after_date_not_given()
    {
        create(IncomingLetter::class, 1, ['date' => '1997-07-15']);
        $this->signIn();
        $after_date = '';

        $viewIncomingLetters = $this -> withoutExceptionHandling()
            ->get('/incoming-letters?filters[date][greater_than]='.$after_date)
            ->assertSuccessful()
            ->assertViewIs('incoming_letters.index')
            ->assertViewHas('incoming_letters')
            ->viewData('incoming_letters');

        $this -> assertCount(1, $viewIncomingLetters);
    }

    /** @test */
    public function user_can_view_filtered_letters_even_if_before_date_not_given()
    {
        create(IncomingLetter::class, 1, ['date' => '1997-07-15']);
        $this ->signIn();
        $before_date = '';

        $viewIncomingLetters = $this -> withoutExceptionHandling()
            ->get('/incoming-letters?before='.$before_date)
            ->assertSuccessful()
            ->assertViewIs('incoming_letters.index')
            ->assertViewHas('incoming_letters')
            ->viewData('incoming_letters');

        $this -> assertCount(1, $viewIncomingLetters);
    }

    /** @test */
    public function user_can_view_filtered_letters_even_if_before_and_after_date_not_given()
    {
        create(IncomingLetter::class, 1, ['date' => '1997-07-15']);
        $this->signIn();
        $after_date = '';
        $before_date = '';

        $viewIncomingLetters = $this -> withoutExceptionHandling()
            ->get('/incoming-letters?filters[date][greater_than]='.$after_date.'&filters[date][less_than]='.$before_date)
            ->assertSuccessful()
            ->assertViewIs('incoming_letters.index')
            ->assertViewHas('incoming_letters')
            ->viewData('incoming_letters');

        $this -> assertCount(1, $viewIncomingLetters);
    }

    /** @test */
    public function user_can_filter_based_on_priority()
    {
        $prioritisedLetters = create(IncomingLetter::class, 2, ['priority' => 1]);
        create(IncomingLetter::class, 1, ['priority' => 2]);
        create(incomingLetter::class, 1, ['priority' => 3]);

        $this->signIn();

        $viewIncomingLetters = $this->withoutExceptionHandling()
                                ->get('/incoming-letters?filters[priority][equals]=1')
                                ->assertSuccessful()
                                ->assertViewIs('incoming_letters.index')
                                ->AssertViewHas('incoming_letters')
                                ->viewData('incoming_letters');

        $this->assertCount(2, $viewIncomingLetters);
        $this->assertTrue($viewIncomingLetters->pluck('id')->contains($prioritisedLetters[0]->id));
        $this->assertTrue($viewIncomingLetters->pluck('id')->contains($prioritisedLetters[1]->id));
    }

    /** @test */
    public function user_can_filter_letters_based_on_recipient()
    {
        $users = create(User::class, 3);
        $recipientIs1 = create(IncomingLetter::class, 2, ['recipient_id' => $users[0]->id]);
        create(IncomingLetter::class, 1, ['recipient_id' => $users[1]->id]);
        create(IncomingLetter::class, 1, ['recipient_id' => $users[2]->id]);

        $this->signIn();

        $viewIncomingLetters = $this -> withoutExceptionHandling()
                                ->get('/incoming-letters?filters[recipient_id][equals]=1')
                                ->assertSuccessful()
                                ->assertViewIs('incoming_letters.index')
                                ->assertViewHas('incoming_letters')
                                ->viewData('incoming_letters');

        $this->assertCount(2, $viewIncomingLetters);
        $this->assertTrue($viewIncomingLetters->pluck('id')->contains($recipientIs1[0]->id));
        $this->assertTrue($viewIncomingLetters->pluck('id')->contains($recipientIs1[1]->id));
    }

    /** @test */
    public function user_can_filter_letters_based_on_sender()
    {
        $sentBy = create(IncomingLetter::class, 2, ['sender' => 'University Office']);
        create(IncomingLetter::class, 1, ['sender' => 'Computer Centre']);
        create(IncomingLetter::class, 1, ['sender' => 'Exam Office']);

        $this->signIn();

        $viewIncomingLetters = $this -> withoutExceptionHandling()
                                ->get('/incoming-letters?filters[sender][equals]=University Office')
                                ->assertSuccessful()
                                ->assertViewIs('incoming_letters.index')
                                ->assertViewHas('incoming_letters')
                                ->viewData('incoming_letters');

        $this->assertCount(2, $viewIncomingLetters);
        $this->assertTrue($viewIncomingLetters->pluck('id')->contains($sentBy[0]->id));
        $this->assertTrue($viewIncomingLetters->pluck('id')->contains($sentBy[1]->id));
    }

    /** @test */
    public function user_can_search_letters_based_on_subject_and_description()
    {
        $sender1 = create(IncomingLetter::class, 1, ['subject' => 'abc def ghi' , 'description' => 'abc jkl ghi']);
        $sender2 = create(IncomingLetter::class, 1, ['subject'=>'jkl abc ghi' , 'description'=>'ghi def jkl']);
        create(IncomingLetter::class, 1, ['subject'=>'abc ghi jkl' , 'description'=>'ghi abc jkl xyz']);

        $this->signIn();

        $viewIncomingLetters = $this->withoutExceptionHandling()
                            ->get('/incoming-letters?search=def')
                            ->assertSuccessful()
                            ->assertViewIs('incoming_letters.index')
                            ->assertViewHas('incoming_letters')
                            ->viewData('incoming_letters');

        $this->assertCount(2, $viewIncomingLetters);
        $this->assertTrue($viewIncomingLetters->pluck('id')->contains($sender1->id));
        $this->assertTrue($viewIncomingLetters->pluck('id')->contains($sender2->id));
    }
}
