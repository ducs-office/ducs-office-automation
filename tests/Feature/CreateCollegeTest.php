<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class CreateCollegeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    /** @test */
    public function admin_can_create_new_college()
    {
        $this->be(factory(User::class)->create());

        $this->post('colleges/',[
            'code' => '123',
            'name' => 'Keshav Mahavidyalaya'
        ])->assertRedirect('/colleges')
        ->assertSessionHasFlag('successs','College added successfully');

        $this->assertEquals(1,colleges::count());
    }
}
