<?php

namespace Tests\Feature;

use App\Models\Cosupervisor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateCosupervisorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function cosupervisor_can_be_updated()
    {
        $this->signIn();

        $coSupervisor = create(Cosupervisor::class, 1, ['name' => $name = 'Bob']);

        $this->assertEquals(1, Cosupervisor::count());
        $this->assertEquals($name, $coSupervisor->fresh()->name);

        $this->withoutExceptionHandling()
        ->patch(route('staff.cosupervisors.update', $coSupervisor), ['name' => $newName = 'John'])
        ->assertRedirect()
        ->assertSessionHasFlash('success', 'Co-supervisor updated successfully!');

        $this->assertEquals($newName, $coSupervisor->fresh()->name);
    }
}
