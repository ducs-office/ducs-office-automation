<?php

namespace Tests\Feature;

use App\Models\Cosupervisor;
use App\Models\Teacher;
use App\Models\User;
use App\Types\UserCategory;
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

        $coSupervisor = create(Cosupervisor::class, 1, [
            'name' => $name = 'Bob',
            'user_id' => null,
        ]);

        $this->assertEquals(1, Cosupervisor::count());
        $this->assertEquals($name, $coSupervisor->fresh()->name);

        $this->withoutExceptionHandling()
        ->patch(route('staff.cosupervisors.update', $coSupervisor), ['name' => $newName = 'John'])
        ->assertRedirect()
        ->assertSessionHasFlash('success', 'Co-supervisor updated successfully!');

        $this->assertEquals($newName, $coSupervisor->fresh()->name);
    }

    /** @test */
    public function cosupervisor_can_not_be_updated_if_it_is_from_existing_user()
    {
        $this->signIn();

        $faculty = create(User::class, 1, ['name' => 'Sharanjeet Kaur', 'category' => UserCategory::FACULTY_TEACHER]);
        $coSupervisor = create(Cosupervisor::class, 1, [
            'user_id' => $faculty->id,
        ]);

        $this->assertTrue($faculty->is(Cosupervisor::first()->professor));

        $this->withExceptionHandling()
            ->patch(route('staff.cosupervisors.update', $coSupervisor), ['name' => 'John'])
            ->assertForbidden();

        $this->assertTrue($faculty->is(Cosupervisor::first()->professor));
    }
}
