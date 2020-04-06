<?php

namespace Tests\Feature;

use App\Cosupervisor;
use App\Teacher;
use App\TeacherProfile;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreCosupervisorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function college_teacher_can_be_stored_as_co_supervisor()
    {
        $this->signIn();

        $teacher = create(Teacher::class);

        $this->withoutExceptionHandling()
            ->post(route('staff.cosupervisors.teachers.store', $teacher))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Co-supervisor added successfully');

        $this->assertEquals(1, Cosupervisor::count());
        $this->assertEquals($teacher->name, Cosupervisor::first()->name);
        $this->assertEquals($teacher->email, Cosupervisor::first()->email);
    }

    /** @test */
    public function faculty_can_be_stored_as_co_supervisor()
    {
        $this->signIn();

        $faculty = create(User::class, 1, ['category' => 'faculty_teacher']);

        $this->withoutExceptionHandling()
            ->post(route('staff.cosupervisors.faculties.store', $faculty))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Co-supervisor added successfully');

        $this->assertEquals(1, Cosupervisor::count());
        $this->assertEquals($faculty->name, Cosupervisor::first()->name);
        $this->assertEquals($faculty->email, Cosupervisor::first()->email);
    }

    /** @test */
    public function new_cosupervisor_can_be_stored()
    {
        $this->signIn();

        $coSupervisor = [
            'name' => 'Abhijeet',
            'email' => 'abhijeet@du.com',
            'designation' => 'teacher',
        ];

        $this->withoutExceptionHandling()
             ->post(route('staff.cosupervisors.store', $coSupervisor))
             ->assertRedirect()
             ->assertSessionHasFlash('success', 'Co-supervisor added successfully');

        $this->assertEquals(1, Cosupervisor::count());
        $this->assertEquals($coSupervisor['name'], Cosupervisor::first()->name);
        $this->assertEquals($coSupervisor['email'], Cosupervisor::first()->email);
    }
}
