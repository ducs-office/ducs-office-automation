<?php

namespace Tests\Feature;

use App\OutgoingLetter;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DeleteOutgoingLettersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_delete_letters()
    {
        $letter = create(OutgoingLetter::class);

        $this->delete("/outgoing-letters/$letter->id")
            ->assertRedirect('/login');

        $this -> assertEquals(1, OutgoingLetter::count());
    }

    /** @test */
    public function user_can_delete_letters_if_they_have_created_and_have_permission_to()
    {
        $this->signIn(create(User::class), 'office');

        $letter = create(OutgoingLetter::class, 1, [
            'creator_id' => auth()->id()
        ]);

        $this->withoutExceptionHandling()
            ->delete("/outgoing-letters/$letter->id")
            ->assertRedirect('/outgoing-letters');

        $this->assertEquals(0, OutgoingLetter::count());
    }

    /** @test */
    public function user_cannot_delete_outgoing_letter_if_permission_not_given()
    {
        $user = create(User::class);
        $role = Role::create(['name' => 'random role']);
        $permission = Permission::firstOrCreate(['name' => 'delete outgoing letters']);

        $letter = create(OutgoingLetter::class);
        
        $role->revokePermissionTo($permission);
        
        $this->signIn($user, $role->name);

        $this->withExceptionHandling()
            ->delete('/outgoing-letters/' . $letter->id)
            ->assertForbidden();

        $this->assertNotNull($letter->fresh());
    }

    /** @test */
    public function user_cannot_delete_outgoing_letter_if_they_did_not_create_it_even_if_they_have_permission()
    {
        $user = create(User::class);
        $role = Role::create(['name' => 'random role']);
        $permission = Permission::firstOrCreate([
            'name' => 'delete outgoing letters'
        ]);

        $letter = create(OutgoingLetter::class, 1, [
            'creator_id' => create(User::class)->id
        ]);
        
        $role->givePermissionTo($permission);
        
        $this->signIn($user, $role->name);

        $this->withExceptionHandling()
            ->delete('/outgoing-letters/' . $letter->id)
            ->assertForbidden();

        $this->assertNotNull($letter->fresh());
    }
}
