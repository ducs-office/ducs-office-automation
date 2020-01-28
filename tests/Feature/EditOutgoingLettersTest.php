<?php

namespace Tests\Feature;

use App\OutgoingLetter;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EditOutgoingLettersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_edit_outgoing_letters()
    {
        $this->signIn();

        $letter = create(OutgoingLetter::class, 1, [
            'creator_id' => auth()->id()
        ]);

        $this->withoutExceptionHandling()
            ->get("/outgoing-letters/$letter->id/edit")
            ->assertSuccessful()
            ->assertViewIs('staff.outgoing_letters.edit')
            ->assertViewHas('outgoing_letter');
    }

    /** @test */
    public function guest_cannot_edit_any_outgoing_letter()
    {
        $this->expectException(AuthenticationException::class);

        $letter = create(OutgoingLetter::class);

        $this->withoutExceptionHandling()
            ->get("/outgoing-letters/$letter->id/edit");
    }

    /** @test */
    public function user_cannot_edit_outgoing_letter_if_permission_not_given()
    {
        $user = create(User::class);
        $role = Role::create(['name' => 'random role']);
        $permission = Permission::firstOrCreate(['name' => 'edit outgoing letters']);

        $letter = create(OutgoingLetter::class, 1, [
            'subject' => $oldSubject = 'old subject'
        ]);

        $role->revokePermissionTo($permission);

        $this->signIn($user, $role->name);

        $this->withExceptionHandling()
            ->get('/outgoing-letters/' . $letter->id . '/edit')
            ->assertForbidden();

        $this->withExceptionHandling()
            ->patch('/outgoing-letters/' . $letter->id, ['subject' => 'new subject'])
            ->assertForbidden();

        $this->assertEquals($oldSubject, $letter->fresh()->subject);
    }

    /** @test */
    public function user_cannot_edit_outgoing_letter_if_they_did_not_create_it_even_if_they_have_permission()
    {
        $user = create(User::class);
        $role = Role::create(['name' => 'random role']);
        $permission = Permission::firstOrCreate(['name' => 'edit outgoing letters']);

        $letter = create(OutgoingLetter::class, 1, [
            'subject' => $oldSubject = 'old subject',
            'creator_id' => create(User::class)->id
        ]);

        $role->givePermissionTo($permission);

        $this->signIn($user, $role->name);

        $this->withExceptionHandling()
            ->get('/outgoing-letters/' . $letter->id . '/edit')
            ->assertForbidden();

        $this->withExceptionHandling()
            ->patch('/outgoing-letters/' . $letter->id, ['subject' => 'new subject'])
            ->assertForbidden();

        $this->assertEquals($oldSubject, $letter->fresh()->subject);
    }
}
