<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\College;
use App\User;

class DeleteCollegeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_not_delete_a_college()
    {
        $college = factory(College::class)->create();

        $this->delete('/colleges/'. $college->id)
            ->assertRedirect('/login');

        $this->assertEquals($college->code, $college->fresh()->code);
    }

    /** @test */
    public function admin_can_delete_any_college()
    {
        $this->be(factory(User::class)->create());

        $college = factory(College::class)->create();

        $this->withoutExceptionHandling()
            ->delete('/colleges/'.$college->id)
            ->assertRedirect('/colleges')
            ->assertSessionHasFlash('success', 'College deleted successfully!');
        
        $this->assertNull($college->fresh(), "College still exists in database");
    }
}
