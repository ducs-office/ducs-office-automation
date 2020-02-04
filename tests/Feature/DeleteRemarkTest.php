<?php

namespace Tests\Feature;

use App\Remark;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DeleteRemarkTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_delete_remark()
    {
        $remark = create(Remark::class);

        $this->withExceptionHandling()
            ->delete(route('staff.remarks.destroy', $remark))
            ->assertRedirect();

        $this->assertEquals(1, Remark::count());
    }

    /** @test */
    public function user_can_delete_remark()
    {
        $this->signIn(create(User::class), 'admin');

        $remark = create(Remark::class, 1, [
            'user_id' => auth()->id()
        ]);

        $this->withoutExceptionHandling()
            ->delete(route('staff.remarks.destroy', $remark));

        $this->assertEquals(0, Remark::count());
    }

    /** @test */
    public function user_cannot_delete_remark_if_they_dont_own_remarks_regardless_of_permission()
    {
        $role = Role::create(['name' => 'random']);
        $permission = Permission::firstOrCreate(['name' => 'delete remarks']);
        $role->givePermissionTo($permission);

        $this->signIn(create(User::class), $role->name);

        $remark = create(Remark::class);

        $this->withExceptionHandling()
            ->delete(route('staff.remarks.destroy', $remark))
            ->assertForbidden();

        $this->assertEquals(1, Remark::count());
    }

    /** @test */
    public function user_cannot_delete_remark_if_doesnt_have_permission()
    {
        $role = Role::create(['name' => 'random']);
        $permission = Permission::firstOrCreate(['name' => 'delete remarks']);
        $role->revokePermissionTo($permission);

        $this->signIn(create(User::class), $role->name);

        $remark = create(Remark::class, 1, [
            'user_id' => auth()->id()
        ]);

        $this->withExceptionHandling()
            ->delete(route('staff.remarks.destroy', $remark))
            ->assertForbidden();

        $this->assertEquals(1, Remark::count());
    }
}
