<?php

namespace Tests\Feature;

use App\OutgoingLetter;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ViewOutgoingLettersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_view_outgoing_letters()
    {
        $this->get(route('staff.outgoing_letters.index'))
            ->assertRedirect();
    }

    /** @test */
    public function user_cannot_view_any_outgoing_letter_if_permission_not_given()
    {
        $user = create(User::class);
        $role = Role::create(['name' => 'random role']);
        $permission = Permission::firstOrCreate(['name' => 'view outgoing letters']);

        $role->revokePermissionTo($permission);

        $this->signIn($user, $role->name);

        $this->withExceptionHandling()
            ->get(route('staff.outgoing_letters.index'))
            ->assertForbidden();
    }

    /** @test */
    public function user_can_view_outgoing_letters()
    {
        $this->signIn();
        create(OutgoingLetter::class, 3);

        $viewOutgoingLetters = $this->withoutExceptionHandling()
            ->get(route('staff.outgoing_letters.index'))
            ->assertSuccessful()
            ->assertViewIs('staff.outgoing_letters.index')
            ->assertViewHas('outgoing_letters')
            ->viewData('outgoing_letters');

        $this->assertInstanceOf(Collection::class, $viewOutgoingLetters);
        $this->assertCount(3, $viewOutgoingLetters);
    }

    /** @test */
    public function view_letters_are_sorted_on_date()
    {
        $this->signIn();
        $letters = create(OutgoingLetter::class, 3);

        $viewData = $this->withExceptionHandling()
            ->get(route('staff.outgoing_letters.index'))
            ->assertSuccessful()
            ->assertViewIs('staff.outgoing_letters.index')
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
        $this->signIn();

        create(OutgoingLetter::class, 3, ['recipient' => 'Exam Office']);
        create(OutgoingLetter::class);
        create(OutgoingLetter::class);

        $recipients = $this->withExceptionHandling()
                ->get(route('staff.outgoing_letters.index'))
                ->assertSuccessful()
                ->assertViewIs('staff.outgoing_letters.index')
                ->assertViewHas('recipients')
                ->viewData('recipients');

        $this->assertCount(3, $recipients);
        $this->assertSame(
            OutgoingLetter::all()->pluck('recipient', 'recipient')->toArray(),
            $recipients->toArray()
        );
    }

    /** @test */
    public function view_has_a_unique_list_of_types()
    {
        $this->signIn();

        create(OutgoingLetter::class, 3, ['type' => 'Bill']);
        create(OutgoingLetter::class, 1, ['type' => 'Notesheet']);
        create(OutgoingLetter::class, 1, ['type' => 'General']);

        $types = $this->withExceptionHandling()
                ->get(route('staff.outgoing_letters.index'))
                ->assertSuccessful()
                ->assertViewIs('staff.outgoing_letters.index')
                ->assertViewHas('types')
                ->viewData('types');

        $this->assertCount(3, $types);
        $this->assertSame(
            OutgoingLetter::all()->pluck('type', 'type')->toArray(),
            $types->toArray()
        );
    }

    /** @test */
    public function view_has_a_unique_list_of_senders()
    {
        $this->signIn();

        create(OutgoingLetter::class, 3, ['sender_id' => 2]);
        create(OutgoingLetter::class);
        create(OutgoingLetter::class);

        $senders = $this->withExceptionHandling()
                ->get(route('staff.outgoing_letters.index'))
                ->assertSuccessful()
                ->assertViewIs('staff.outgoing_letters.index')
                ->assertViewHas('senders')
                ->viewData('senders');

        $this->assertCount(3, $senders);
        $this->assertSame(
            OutgoingLetter::with('sender')->get()->pluck('sender.name', 'sender_id')->toArray(),
            $senders->toArray()
        );
    }

    /** @test */
    public function view_has_a_unique_list_of_creators()
    {
        $user = $this->signIn();

        create(OutgoingLetter::class, 3, ['creator_id' => $user->id]);
        create(OutgoingLetter::class);
        create(OutgoingLetter::class);

        $creators = $this->withExceptionHandling()
                ->get(route('staff.outgoing_letters.index'))
                ->assertSuccessful()
                ->assertViewIs('staff.outgoing_letters.index')
                ->assertViewHas('creators')
                ->viewData('creators');

        $this->assertCount(3, $creators);
        $this->assertSame(
            OutgoingLetter::with('creator')->get()->pluck('creator.name', 'creator_id')->toArray(),
            $creators->toArray()
        );
    }
}
