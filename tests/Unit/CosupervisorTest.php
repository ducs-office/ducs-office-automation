<?php

namespace Tests\Unit;

use App\Models\College;
use App\Models\Cosupervisor;
use App\Models\Teacher;
use App\Models\User;
use App\Types\TeacherStatus;
use App\Types\UserCategory;
use App\Types\UserType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CosupervisorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function cosupervisor_is_related_to_an_existing_user()
    {
        $faculty = create(User::class, ['category' => UserCategory::FACULTY_TEACHER]);
        $cosupervisor = create(Cosupervisor::class, 1, [
            'professor_type' => User::class,
            'professor_id' => $faculty->id,
        ]);

        $this->assertInstanceOf(BelongsTo::class, $cosupervisor->professor());
        $this->assertTrue($faculty->is($cosupervisor->professor));

        $teacher = create(User::class, 1, ['category' => UserCategory::COLLEGE_TEACHER]);

        $teacherCosupervisor = create(Cosupervisor::class, 1, [
            'professor_type' => User::class,
            'professor_id' => $teacher->id,
        ]);

        $this->assertTrue($teacher->is($teacherCosupervisor->professor));
    }

    /** @test */
    public function cosupervisor_details_for_existing_professor_can_be_accessed_as_direct_properties()
    {
        $existingProfessor = create(User::class);

        $cosupervisor = create(Cosupervisor::class, 1, [
            'professor_type' => User::class,
            'professor_id' => $existingProfessor->id,
        ]);

        $this->assertEquals($existingProfessor->name, $cosupervisor->name);
        $this->assertEquals($existingProfessor->email, $cosupervisor->email);
        $this->assertEquals($existingProfessor->designation, $cosupervisor->designation);
        $this->assertEquals($existingProfessor->college->name, $cosupervisor->affiliation);
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
