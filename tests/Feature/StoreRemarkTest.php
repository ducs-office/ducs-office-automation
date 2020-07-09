<?php

namespace Tests\Feature;

use App\Models\OutgoingLetter;
use App\Models\Remark;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\str;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class StoreRemarkTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_store_remark()
    {
        $letter = create(OutgoingLetter::class);

        $this->withExceptionHandling()
            ->post(route('staff.outgoing_letters.remarks.store', $letter))
            ->assertRedirect(route('login-form'));

        $this->assertEquals(0, Remark::count());
    }

    /** @test */
    public function user_cannot_store_remark_if_they_donot_have_permission()
    {
        $role = Role::firstOrCreate(['name' => 'Not a Remarker']);
        $role->revokePermissionTo('letter remarks:create');
        $this->signIn(create(User::class), $role->name);

        $letter = create(OutgoingLetter::class);

        $this->withExceptionHandling()
            ->post(route('staff.outgoing_letters.remarks.store', $letter))
            ->assertForbidden();
    }

    /** @test */
    public function user_can_create_remark_if_they_are_permitted_to()
    {
        $this->signIn(create(User::class), 'admin');

        $letter = create(OutgoingLetter::class);

        $this->withoutExceptionHandling()
            ->post(route('staff.outgoing_letters.remarks.store', $letter), [
                'description' => 'Not received by University',
            ]);

        $this->assertEquals(1, Remark::count());
    }

    /** @test */
    public function request_validates_description_field_cannot_be_null()
    {
        $this->signIn(create(User::class), 'admin');

        $letter = create(OutgoingLetter::class);
        $remark = ['description' => ''];

        try {
            $this->withoutExceptionHandling()
                ->post(route('staff.outgoing_letters.remarks.store', $letter), $remark);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('description', $e->errors());
        }

        $this->assertEquals(0, Remark::count());
    }

    /** @test */
    public function request_validates_description_field_minlimit_2()
    {
        $this->signIn(create(User::class), 'admin');

        $letter = create(OutgoingLetter::class);
        $remark = ['description' => Str::random(1)];

        try {
            $this->withoutExceptionHandling()
                ->post(route('staff.outgoing_letters.remarks.store', $letter), $remark);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('description', $e->errors());
        }

        $this->assertEquals(0, Remark::count());
    }

    /** @test */
    public function request_validates_description_field_maxlimit_255()
    {
        $this->signIn(create(User::class), 'admin');

        $letter = create(OutgoingLetter::class);
        $remark = ['description' => Str::random(256)];

        try {
            $this->withoutExceptionHandling()
                ->post(route('staff.outgoing_letters.remarks.store', $letter), $remark);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('description', $e->errors());
        }

        $this->assertEquals(0, Remark::count());
    }
}
