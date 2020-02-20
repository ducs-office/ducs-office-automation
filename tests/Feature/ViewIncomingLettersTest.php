<?php

namespace Tests\Feature;

use App\Handover;
use App\Http\Controllers\IncomingLetterController;
use App\IncomingLetter;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ViewIncomingLettersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_not_view_incoming_letters()
    {
        $this->expectException(AuthenticationException::class);

        $this->withoutExceptionHandling()
            ->get(route('staff.incoming_letters.index'))
            ->assertRedirect();
    }

    /** @test */
    public function user_can_view_incoming_letters()
    {
        $this->signIn();
        create(IncomingLetter::class, 2);

        $viewIncomingLetters = $this->withoutExceptionHandling()
                    ->get(route('staff.incoming_letters.index'))
                    ->assertSuccessful()
                    ->assertViewIs('staff.incoming_letters.index')
                    ->assertViewHas('incomingLetters')
                    ->viewData('incomingLetters');

        $this->assertInstanceOf(Collection::class, $viewIncomingLetters);
        $this->assertCount(2, $viewIncomingLetters);
    }

    /** @test */
    public function view_letters_are_sorted_on_date()
    {
        $this->signIn();
        $letters = create(IncomingLetter::class, 3);

        $view_data = $this->withExceptionHandling()
                    ->get(route('staff.incoming_letters.index'))
                    ->assertSuccessful()
                    ->assertViewIs('staff.incoming_letters.index')
                    ->assertViewHas('incomingLetters')
                    ->viewData('incomingLetters');

        $letters = $letters->sortByDesc('date');
        $sorted_letter_ids = $letters->pluck('id')->toArray();
        $view_data_ids = $view_data->pluck('id')->toArray();
        $this->assertSame($sorted_letter_ids, $view_data_ids);
    }

    /** @test */
    public function view_has_a_unique_list_of_recipients()
    {
        $this->signIn();

        create(IncomingLetter::class, 3, ['recipient_id' => 1]);
        create(IncomingLetter::class);
        create(IncomingLetter::class);

        $recipients = $this->withExceptionHandling()
                ->get(route('staff.incoming_letters.index'))
                ->assertSuccessful()
                ->assertViewIs('staff.incoming_letters.index')
                ->assertViewHas('recipients')
                ->viewData('recipients');

        $this->assertCount(3, $recipients);
        $this->assertSame(
            IncomingLetter::with('recipient')->get()->pluck('recipient.name', 'recipient_id')->toArray(),
            $recipients->toArray()
        );
    }

    /** @test */
    public function view_has_an_unique_list_of_senders()
    {
        $this->signIn();
        create(IncomingLetter::class, 3, ['sender' => 'University Office']);
        create(IncomingLetter::class);
        create(IncomingLetter::class);

        $senders = $this->withoutExceptionHandling()
                    ->get(route('staff.incoming_letters.index'))
                    ->assertSuccessful()
                    ->assertViewIs('staff.incoming_letters.index')
                    ->assertViewHas('senders')
                    ->viewData('senders');

        $this->assertCount(3, $senders);
        $this->assertSame(
            IncomingLetter::all()->pluck('sender', 'sender')->toArray(),
            $senders->toArray()
        );
    }

    /** @test */
    public function view_has_a_unique_list_of_priorities()
    {
        $this->signIn();

        create(IncomingLetter::class, 3, ['priority' => 1]);
        create(IncomingLetter::class, 1, ['priority' => 2]);
        create(IncomingLetter::class, 1, ['priority' => 3]);

        $priorities = $this->withoutExceptionHandling()
                        ->get(route('staff.incoming_letters.index'))
                        ->assertSuccessful()
                        ->assertViewIs('staff.incoming_letters.index')
                        ->assertViewHas('priorities')
                        ->viewData('priorities');

        $this->assertSame(config('options.incoming_letters.priorities'), $priorities);
    }
}
