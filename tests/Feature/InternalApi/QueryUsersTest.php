<?php

namespace Tests\Feature\InternalApi;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QueryUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_query_other_users_by_keyword()
    {
        $john = create(User::class, 1, ['name' => 'John']);

        $this->getJson('/api/users?q=' . $john->name)
            ->assertUnauthorized();
    }

    /** @test */
    public function user_can_query_other_users_by_keyword()
    {
        $john = create(User::class, 1, ['name' => 'John Doe']);
        $jane = create(User::class, 1, ['name' => 'Jane Doe']);
        $mary = create(User::class, 1, ['name' => 'Mary John']);

        $this->withoutExceptionHandling()->signIn($jane);

        $users = $this->getJson('/api/users?q=John')
            ->assertSuccessful()
            ->json();

        $this->assertCount(2, $users);

        $this->assertArrayHasKey('name', $users[0]);
        $this->assertEquals($john->name, $users[0]['name']);
        $this->assertArrayHasKey('email', $users[0]);
        $this->assertEquals($john->email, $users[0]['email']);

        $this->assertArrayHasKey('name', $users[1]);
        $this->assertEquals($mary->name, $users[1]['name']);
        $this->assertArrayHasKey('email', $users[1]);
        $this->assertEquals($mary->email, $users[1]['email']);
    }

    /** @test */
    public function if_query_matches_exact_email_only_that_user_is_returned()
    {
        $maliciousUser = create(User::class, 1, ['name' => 'john@example.com']);
        $john = create(User::class, 1, ['name' => 'John', 'email' => 'john@example.com']);
        $jane = create(User::class, 1, ['name' => 'Jane Doe']);

        $this->withoutExceptionHandling()->signIn($jane);

        $users = $this->getJson('/api/users?q=john@example.com')
            ->assertSuccessful()
            ->json();

        $this->assertCount(1, $users);

        $this->assertArrayHasKey('name', $users[0]);
        $this->assertEquals($john->name, $users[0]['name']);
        $this->assertArrayHasKey('email', $users[0]);
        $this->assertEquals($john->email, $users[0]['email']);
    }

    /** @test */
    public function without_query_parameter_no_user_is_returned()
    {
        $john = create(User::class, 1, ['name' => 'John']);

        $this->signIn($john);

        $users = $this->withoutExceptionHandling()
            ->getJson('/api/users?q=')
            ->assertSuccessful()
            ->json();

        $this->assertCount(0, $users);
    }

    /** @test */
    public function limit_query_parameter_limits_number_of_matches_returned()
    {
        $john = create(User::class, 1, ['name' => 'John Doe']);
        create(User::class, 1, ['name' => 'Jane Doe']);
        create(User::class, 1, ['name' => 'Joe Doe']);
        create(User::class, 1, ['name' => 'Joe Doe']);
        create(User::class, 1, ['name' => 'Jon Doe']);

        $this->signIn($john);

        $users = $this->getJson('/api/users?q=Jo&limit=2')
            ->assertSuccessful()
            ->json();
        $this->assertCount(2, $users);

        $users = $this->getJson('/api/users?q=Jo')
            ->assertSuccessful()
            ->json();
        $this->assertCount(4, $users);
    }
}
