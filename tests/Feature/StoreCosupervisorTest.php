<?php

namespace Tests\Feature;

use App\Models\College;
use App\Models\Cosupervisor;
use App\Models\ExternalAuthority;
use App\Models\Teacher;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Types\UserCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
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

        $this->assertEquals(1, User::cosupervisors()->count());
        $this->assertEquals($teacher->name, User::cosupervisors()->first()->name);
        $this->assertEquals($teacher->email, User::cosupervisors()->first()->email);
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

        $this->assertEquals(1, User::cosupervisors()->count());
        $this->assertEquals($faculty->name, User::cosupervisors()->first()->name);
        $this->assertEquals($faculty->email, User::cosupervisors()->first()->email);
    }

    /** @test */
    public function faculty_who_is_supervisor_can_not_be_stored_as_cosupervisor()
    {
        $this->signIn();

        $facultySupervisor = factory(User::class)->states('supervisor')->create();

        $this->assertEquals(0, User::cosupervisors()->count());

        $this->withExceptionHandling()
            ->post(route('staff.cosupervisors.store'), [
                'user_id' => $facultySupervisor->id,
            ])
            ->assertSessionHasErrors('user_id');

        $this->assertEquals(0, User::cosupervisors()->count());
    }

    /** @test */
    public function user_who_is_not_a_faculty_teacher_can_not_be_stored_as_cosupervisor()
    {
        $this->signIn();

        $faculty = create(User::class, 1, ['category' => UserCategory::OFFICE_STAFF]);

        $this->assertEquals(0, User::cosupervisors()->count());

        $this->withExceptionHandling()
            ->post(route('staff.cosupervisors.store'), [
                'user_id' => $faculty->id,
            ])
            ->assertSessionHasErrors('user_id');

        $this->assertEquals(0, User::cosupervisors()->count());
    }

    /** @test */
    public function user_who_is_a_faculty_teacher_can_be_stored_as_cosupervisor()
    {
        $this->signIn();

        $faculty = create(User::class, 1, ['category' => UserCategory::FACULTY_TEACHER]);

        $this->assertEquals(0, User::cosupervisors()->count());

        $this->withoutExceptionHandling()
            ->post(route('staff.cosupervisors.store'), [
                'user_id' => $faculty->id,
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Co-supervisor added successfully');

        $this->assertEquals(1, User::cosupervisors()->count());
    }

    // /** @test */
    // public function new__external_cosupervisor_can_be_stored()
    // {
    //     $this->signIn();

    //     $newExternal = [
    //         'name' => 'Abhijeet',
    //         'email' => 'abhijeet@du.com',
    //         'designation' => 'teacher',
    //         'affiliation' => 'DUCS, DU',
    //     ];

    //     $this->withoutExceptionHandling()
    //          ->post(route('staff.cosupervisors.store', $newExternal))
    //          ->assertRedirect()
    //          ->assertSessionHasFlash('success', 'Co-supervisor added successfully');

    //     $this->assertCount(1, $extrnals = ExternalAuthority::all());
    //     $this->assertEquals($newExternal['name'], $extrnals->first()->name);
    //     $this->assertEquals($newExternal['email'], $extrnals->first()->email);

    //     $this->assertCount(1, $cosupervisors = ExternalAuthority::cosupervisors()->get());
    //     $this->assertEquals($extrnals->first()->id, $cosupervisors->first()->id);
    // }

    // /** @test */
    // public function existing_external_can_be_made_cosupervisor()
    // {
    //     $this->signIn();

    //     $external = create(ExternalAuthority::class);

    //     $this->withoutExceptionHandling()
    //          ->post(route('staff.cosupervisors.store', ['external_id' => $external->id]))
    //          ->assertRedirect()
    //          ->assertSessionHasFlash('success', 'Co-supervisor added successfully');

    //     $this->assertCount(1, $cosupervisors = ExternalAuthority::cosupervisors()->get());
    //     $this->assertEquals($external->first()->id, $cosupervisors->first()->id);
    // }
}
