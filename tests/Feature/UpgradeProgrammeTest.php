<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Auth\AuthenticationException;
use App\Programme;

class UpgradeProgrammeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_upgrade_programme()
    {
        $this->signIn();

        $programme = create(Programme::class);

        $this->withoutExceptionHandling()
            ->get("/programmes/$programme->id/upgrade")
            ->assertSuccessful()
            ->assertViewIs('programmes.upgrade')
            ->assertViewHas('programme');
    }

    /** @test */
    public function guest_cannot_upgrade_any_programme()
    {
        $this->expectException(AuthenticationException::class);

        $programme = create(Programme::class);

        $this->withoutExceptionHandling()
            ->get("/programmes/$programme->id/upgrade");
    }
}
