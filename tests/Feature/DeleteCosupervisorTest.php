<?php

namespace Tests\Feature;

use App\Cosupervisor;
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

        $coSupervisor = create(Cosupervisor::class);

        $this->assertEquals(1, Cosupervisor::count());

        $this->withoutExceptionHandling()
        ->delete(route('staff.cosupervisors.destroy', $coSupervisor))
        ->assertRedirect()
        ->assertSessionHasFlash('success', 'Co-supervisor deleted successfully!');

        $this->assertEquals(0, Cosupervisor::count());
    }
}
