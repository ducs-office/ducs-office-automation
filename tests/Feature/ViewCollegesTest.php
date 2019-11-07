<?php

namespace Tests\Feature;

use App\User;
use App\College;
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
            ->get('/colleges')
            ->assertRedirect('/login');
    }

    /** @test */
    public function admin_can_view_colleges()
    {
        $this->signIn();

        create(College::class, 3);

        $viewData = $this->withoutExceptionHandling()
                    ->get('/colleges')
                    ->assertViewIs('colleges.index')
                    ->assertViewHas('colleges')
                    ->viewData('colleges');

        $this->assertCount(3, $viewData);
    }
}
