<?php

namespace Tests\Feature;

use App\Models\College;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteCollegeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_not_delete_a_college()
    {
        $college = create(College::class);

        $this->delete(route('staff.colleges.destroy', $college))
            ->assertRedirect(route('login_form'));

        $this->assertEquals($college->code, $college->fresh()->code);
    }

    /** @test */
    public function admin_can_delete_any_college()
    {
        $this->signIn();

        $college = create(College::class);

        $this->withoutExceptionHandling()
            ->delete(route('staff.colleges.destroy', $college))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'College deleted successfully!');

        $this->assertNull($college->fresh(), 'College still exists in database');
    }
}
