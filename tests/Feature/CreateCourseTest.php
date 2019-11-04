<?php

namespace Tests\Feature;

use App\Programme;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateProgrammeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_create_new_programme()
    {
        $this->signIn();
        $this->withoutExceptionHandling();
        $this->post('/programmes', [
            'code' => 'MCS',
            'wef' => '2019-08-12',
            'name' => 'M.Sc. Computer Science',
        ])->assertRedirect('/programmes')
        ->assertSessionHasFlash('success', 'Programme created successfully');

        $this->assertEquals(1, Programme::count());
    }
}
