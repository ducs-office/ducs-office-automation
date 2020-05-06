<?php

namespace Tests\Unit;

use App\Models\College;
use App\Models\Cosupervisor;
use App\Models\Teacher;
use App\Models\User;
use App\Types\TeacherStatus;
use App\Types\UserType;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CosupervisorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function cosupervisor_is_either_a_college_teacher_or_faculty_via_morph_to()
    {
        $cosupervisor = create(Cosupervisor::class);

        $this->assertInstanceOf(MorphTo::class, $cosupervisor->professor());

        $teacher = create(Teacher::class);
        $teacherCosupervisor = create(Cosupervisor::class, 1, [
            'professor_type' => Teacher::class,
            'professor_id' => $teacher->id,
        ]);

        $this->assertTrue($teacher->is($teacherCosupervisor->professor));

        $faculty = create(User::class, 1, ['type' => UserType::FACULTY_TEACHER]);

        $facultyCosupervisor = create(Cosupervisor::class, 1, [
            'professor_type' => User::class,
            'professor_id' => $faculty->id,
        ]);

        $this->assertTrue($faculty->is($facultyCosupervisor->professor));
    }

    /** @test */
    public function faculty_teacher_cosupervisor_details_can_be_accessed_as_direct_properties()
    {
        $facultyTeacher = create(User::class);

        $cosupervisor = create(Cosupervisor::class, 1, [
            'professor_type' => User::class,
            'professor_id' => $facultyTeacher->id,
        ]);

        $this->assertEquals($facultyTeacher->name, $cosupervisor->name);
        $this->assertEquals($facultyTeacher->email, $cosupervisor->email);
        $this->assertEquals('Professor', $cosupervisor->designation);
        $this->assertEquals('DUCS', $cosupervisor->affiliation);
    }

    /** @test */
    public function college_teacher_cosupervisor_details_can_be_accessed_as_direct_properties()
    {
        $collegeTeacher = create(Teacher::class);
        $college = create(College::class);

        $collegeTeacher->profile()->update([
            'college_id' => $college->id,
            'designation' => TeacherStatus::PERMANENT,
        ]);

        $cosupervisor = create(Cosupervisor::class, 1, [
            'professor_type' => Teacher::class,
            'professor_id' => $collegeTeacher->id,
        ]);

        $this->assertEquals($collegeTeacher->name, $cosupervisor->name);
        $this->assertEquals($collegeTeacher->email, $cosupervisor->email);
        $this->assertEquals('Professor', $cosupervisor->designation);
        $this->assertEquals($collegeTeacher->profile->college->name, $cosupervisor->affiliation);
    }

    /** @test */
    public function external_cosupervisor_details_can_be_accessed_as_direct_properties()
    {
        $external = create(Cosupervisor::class, 1, [
            'professor_type' => null,
            'professor_id' => null,
            'name' => $name = 'Cosupervisor Name',
            'email' => $email = 'cosup@gmail.com',
            'designation' => $designation = 'Head of Department',
            'affiliation' => $affiliation = 'Department of Mathematics',
        ]);

        $this->assertEquals($name, $external->name);
        $this->assertEquals($email, $external->email);
        $this->assertEquals($designation, $external->designation);
        $this->assertEquals($affiliation, $external->affiliation);
    }
}
