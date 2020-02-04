<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\OutgoingLetter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateOutgoingLettersTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake();
    }

    /** @test */
    public function user_can_fill_outgoing_letters_form()
    {
        $this->signIn();

        $this->withoutExceptionHandling()
            ->get(route('staff.outgoing_letters.create'))
            ->assertSuccessful()
            ->assertViewIs('staff.outgoing_letters.create');
    }

    /** @test */
    public function guest_cannot_fill_outgoing_letters_form()
    {
        $this->withExceptionHandling()
            ->get(route('staff.outgoing_letters.create'))
            ->assertRedirect('login');
    }

    /** @test */
    public function user_cannot_create_outgoing_letter_if_permission_not_given()
    {
        $user = create(User::class);
        $role = Role::create(['name' => 'random role']);
        $permission = Permission::firstOrCreate([
            'name' => 'create outgoing letters'
        ]);

        $role->revokePermissionTo($permission);

        $this->signIn($user, $role->name);

        $this->withExceptionHandling()
            ->get(route('staff.outgoing_letters.create'))
            ->assertForbidden();

        $this->withExceptionHandling()
            ->post(route('staff.outgoing_letters.store'))
            ->assertForbidden();
    }
}
