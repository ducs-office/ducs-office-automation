<?php

namespace Tests\Feature;

use App\User;
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
        factory(College::class,3)->create();

        $this->withoutExceptionHandling()
            ->get('/colleges')
            ->assertRedirect('/login');
    }

    public function admin_can_view_colleges()
    {
        $this->be(factory(User::class)->create());

        factory(College::class,3)->create();

        $viewData = $this->withoutExceptionHandling()
                    ->get('/colleges')
                    ->assertViewIs('/colleges')
                    ->assertViewHas('colleges')
                    ->viewData('colleges');
                    
        $this->assertCount(3,$viewData);
    }
}
