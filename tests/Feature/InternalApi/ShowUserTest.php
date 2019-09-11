<?php

namespace Tests\Feature\InternalApi;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowUserTest extends TestCase
{

    use RefreshDatabase;
    
    /** @test */
    public function user_can_query_another_user_containing_id_name_email() {
        $himani_mam = factory(User::class)->create(['id' => 1]);
        factory(User::class)->create(['id' => 2]);

        $this->withoutExceptionHandling();
        
        $user = $this->be($himani_mam)
            ->getJson('/api/users/2')
            ->assertSuccessful()
            ->json();

        $this->assertArrayHasKey('id', $user);
        $this->assertArrayHasKey('name', $user);
        $this->assertArrayHasKey('email', $user);
    }
}
