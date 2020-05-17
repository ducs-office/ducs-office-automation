<?php

namespace Tests\Feature;

use App\Models\OutgoingLetter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DeleteOutgoingLettersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_delete_letters()
    {
        $letter = create(OutgoingLetter::class);

        $this->delete(route('staff.outgoing_letters.destroy', $letter))
            ->assertRedirect(route('login-form'));

        $this->assertEquals(1, OutgoingLetter::count());
    }

    /** @test */
    public function user_can_delete_letters_if_they_have_created_and_have_permission_to()
    {
        $this->signIn(create(User::class), 'admin');

        $letter = create(OutgoingLetter::class, 1, [
            'creator_id' => auth()->id(),
        ]);

        $this->withoutExceptionHandling()
            ->delete(route('staff.outgoing_letters.destroy', $letter))
            ->assertRedirect();

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
            ->delete(route('staff.outgoing_letters.destroy', $letter))
            ->assertForbidden();

        $this->assertNotNull($letter->fresh());
    }

    /** @test */
    public function user_cannot_delete_outgoing_letter_if_they_did_not_create_it_even_if_they_have_permission()
    {
        $user = create(User::class);
        $role = Role::create(['name' => 'random role']);
        $permission = Permission::firstOrCreate([
            'name' => 'delete outgoing letters',
        ]);

        $letter = create(OutgoingLetter::class, 1, [
            'creator_id' => create(User::class)->id,
        ]);

        $role->givePermissionTo($permission);

        $this->signIn($user, $role->name);

        $this->withExceptionHandling()
            ->delete(route('staff.outgoing_letters.destroy', $letter))
            ->assertForbidden();

        $this->assertNotNull($letter->fresh());
    }
}
