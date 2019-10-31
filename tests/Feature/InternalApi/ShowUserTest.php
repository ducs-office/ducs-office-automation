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
    public function user_can_query_another_user_containing_id_name_email()
    {
        $himani_mam = create(User::class, 1, ['id' => 1]);
        create(User::class, 1, ['id' => 2]);

        $this->signIn($himani_mam);

        $user = $this->withoutExceptionHandling()
            ->getJson('/api/users/2')
            ->assertSuccessful()
            ->json();

        $this->assertArrayHasKey('id', $user);
        $this->assertArrayHasKey('name', $user);
        $this->assertArrayHasKey('email', $user);
    }
}
