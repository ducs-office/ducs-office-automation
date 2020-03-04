<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_may_have_a_supervisor_profile()
    {
        $user = create(User::class);

        $this->assertInstanceOf(MorphOne::class, $user->supervisorProfile());

        $this->assertNull($user->supervisorProfile);

        $profile = $user->supervisorProfile()->create();

        $this->assertTrue($profile->is($user->fresh()->supervisorProfile));
    }
}
