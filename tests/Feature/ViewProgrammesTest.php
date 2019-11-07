<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewProgrammesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_view_programmes()
    {
        $programmes = create('App\Programme', 3);

        $this->withExceptionHandling();

        $this->get('/programmes')->assertRedirect('/login');
    }
    /** @test */
    public function admin_can_view_all_programmes()
    {
        $this->signIn();

        $programmes = create('App\Programme', 3);

        $this->withoutExceptionHandling();

        $viewData = $this->get('/programmes')->assertViewIs('programmes.index')
            ->assertViewHas('programmes')
            ->viewData('programmes');

        $this->assertCount(3, $viewData);
        $this->assertEquals(
            $programmes->sortByDesc('created_at')->first()->toArray(),
            $viewData->first()->toArray()
        ); //first created is at last
    }
}
