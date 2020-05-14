<?php

namespace Tests\Feature;

use App\Models\Cosupervisor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteCosupervisorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function cosupervisor_can_be_deleted()
    {
        $this->signIn();

        $cosupervisor = factory(User::class)->states('cosupervisor')->create();

        $this->withoutExceptionHandling()
        ->delete(route('staff.cosupervisors.destroy', $cosupervisor))
        ->assertRedirect()
        ->assertSessionHasFlash('success', 'Co-supervisor deleted successfully!');

        $this->assertFalse($cosupervisor->fresh()->isCosupervisor());
    }
}
