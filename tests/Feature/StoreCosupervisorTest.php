<?php

namespace Tests\Feature;

use App\Models\College;
use App\Models\Cosupervisor;
use App\Models\SupervisorProfile;
use App\Models\Teacher;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Types\UserCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class StoreCosupervisorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function college_teacher_who_is_not_supervisor_can_be_stored_as_co_supervisor()
    {
        $this->signIn();

        $teacher = create(User::class, 1, [
            'category' => UserCategory::COLLEGE_TEACHER,
            'college_id' => create(College::class)->id,
        ]);

        $this->withoutExceptionHandling()
            ->post(route('staff.cosupervisors.store'), [
                'user_id' => $teacher->id,
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Co-supervisor added successfully');

        $this->assertEquals(1, Cosupervisor::count());
        $this->assertEquals($teacher->name, Cosupervisor::first()->name);
        $this->assertEquals($teacher->email, Cosupervisor::first()->email);
    }

    /** @test */
    public function college_teacher_who_is_supervisor_can_not_be_stored_as_cosupervisor()
    {
        $this->signIn();

        $teacher = create(User::class, 1, [
            'category' => UserCategory::COLLEGE_TEACHER,
            'college_id' => create(College::class)->id,
        ]);
        $teacher->supervisorProfile()->create();

        $this->withExceptionHandling()
            ->post(route('staff.cosupervisors.store'), [
                'user_id' => $teacher->id,
            ])
            ->assertForbidden();

        $this->assertEquals(0, Cosupervisor::count());
    }

    /** @test */
    public function faculty_who_is_not_supervisor_can_be_stored_as_co_supervisor()
    {
        $this->signIn();

        $faculty = create(User::class, 1, ['category' => UserCategory::FACULTY_TEACHER]);

        $this->withoutExceptionHandling()
            ->post(route('staff.cosupervisors.store'), [
                'user_id' => $faculty->id,
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Co-supervisor added successfully');

        $this->assertEquals(1, Cosupervisor::count());
        $this->assertEquals($faculty->name, Cosupervisor::first()->name);
        $this->assertEquals($faculty->email, Cosupervisor::first()->email);
    }

    /** @test */
    public function faculty_who_is_supervisor_can_not_be_stored_as_cosupervisor()
    {
        $this->signIn();

        $faculty = create(User::class, 1, ['category' => UserCategory::FACULTY_TEACHER]);
        $faculty->supervisorProfile()->create();

        $this->assertEquals(0, Cosupervisor::count());

        $this->withExceptionHandling()
            ->post(route('staff.cosupervisors.store'), [
                'user_id' => $faculty->id,
            ])
            ->assertForbidden();

        $this->assertEquals(0, Cosupervisor::count());
    }

    /** @test */
    public function user_who_is_not_a_faculty_teacher_can_not_be_stored_as_cosupervisor()
    {
        $this->signIn();

        $faculty = create(User::class, 1, ['category' => UserCategory::OFFICE_STAFF]);

        $this->assertEquals(0, Cosupervisor::count());

        $this->withExceptionHandling()
            ->post(route('staff.cosupervisors.store'), [
                'user_id' => $faculty->id,
            ])
            ->assertForbidden();

        $this->assertEquals(0, Cosupervisor::count());
    }

    /** @test */
    public function user_who_is_a_faculty_teacher_not_be_stored_as_cosupervisor()
    {
        $this->signIn();

        $faculty = create(User::class, 1, ['category' => UserCategory::FACULTY_TEACHER]);

        $this->assertEquals(0, Cosupervisor::count());

        $this->withoutExceptionHandling()
            ->post(route('staff.cosupervisors.store'), [
                'user_id' => $faculty->id,
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Co-supervisor added successfully');

        $this->assertEquals(1, Cosupervisor::count());
    }

    /** @test */
    public function new_cosupervisor_can_be_stored()
    {
        $this->signIn();

        $coSupervisor = [
            'name' => 'Abhijeet',
            'email' => 'abhijeet@du.com',
            'designation' => 'teacher',
            'affiliation' => 'DUCS, DU',
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
