<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\College;

class UpdateCollegeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_not_update_a_college()
    {
        $college = factory(College::class)->create();

        $this->withExceptionHandling()
            ->patch('/colleges/'. $college->id, ['code' => 'code1'])
            ->assertRedirect('/login');


        $this->assertEquals($college->code, $college->fresh()->code);
    }
    /** @test */
    public function admin_can_update_a_college_code()
    {
        $this->be(factory(User::class)->create());

        $college = factory(College::class)->create();

        $this->withoutExceptionHandling()
            ->patch('/colleges/'. $college->id, ['code' => $new_code = 'code1'])
            ->assertRedirect('/colleges')
            ->assertSessionHasFlash('success', 'College updated successfully');

        $this->assertEquals(1, College::count());
        $this->assertEquals($new_code, $college->fresh()->code);

    }

    /** @test */
    public function admin_can_update_a_college_name()
    {
        $this->be(factory(User::class)->create());

        $college = factory(College::class)->create();

        $this->withoutExceptionHandling()
            ->patch('/colleges/'. $college->id, ['name' => $new_name = 'new name'])
            ->assertRedirect('/colleges')
            ->assertSessionHasFlash('success', 'College updated successfully');

        $this->assertEquals(1, College::count());
        $this->assertEquals($new_name, $college->fresh()->name);

    }
}
