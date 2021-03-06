<?php

namespace Tests\Feature;

use App\Models\College;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewCollegesTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_view_colleges()
    {
        create(College::class, 3);

        $this->withExceptionHandling()
            ->get(route('staff.colleges.index'))
            ->assertRedirect();
    }

    /** @test */
    public function admin_can_view_colleges()
    {
        $this->signIn(create(User::class, 1, ['college_id' => null]));

        create(College::class, 3);

        $view_data = $this->withoutExceptionHandling()
                    ->get(route('staff.colleges.index'))
                    ->assertViewIs('staff.colleges.index')
                    ->assertViewHas('colleges')
                    ->viewData('colleges');

        $this->assertCount(3, $view_data);
    }
}
