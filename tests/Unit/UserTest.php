<?php

namespace Tests\Unit;

use App\Models\Cosupervisor;
use App\Models\Publication;
use App\Models\User;
use App\Types\UserCategory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_gives_full_name()
    {
        $user = new User();
        $user->first_name = 'John';
        $user->last_name = 'Doe';

        $this->assertEquals('John Doe', $user->name);
    }

    /** @test */
    public function it_sets_full_name()
    {
        $user = new User();
        $user->name = 'John Doe';

        $this->assertEquals('John', $user->first_name);
        $this->assertEquals('Doe', $user->last_name);
    }

    /** @test */
    public function isSupervisor_method_gives_boolean_indicating_whether_or_not_user_is_a_supervisor()
    {
        $user = create(User::class);

        $this->assertFalse($user->isSupervisor());

        $user->update(['is_supervisor' => true]);

        $this->assertTrue($user->fresh()->isSupervisor());
    }

    /** @test */
    public function canBecomeSupervisor_checks_only_college_teacher_faculty_teacher_can_become_supervisors()
    {
        $officeStaff = create(User::class, 1, ['category' => UserCategory::OFFICE_STAFF]);
        $this->assertFalse($officeStaff->canBecomeSupervisor());

        $labStaff = create(User::class, 1, ['category' => UserCategory::LAB_STAFF]);
        $this->assertFalse($labStaff->canBecomeSupervisor());

        $facultyTeacher = create(User::class, 1, ['category' => UserCategory::FACULTY_TEACHER]);
        $this->assertTrue($facultyTeacher->canBecomeSupervisor());

        $collegeTeacher = create(User::class, 1, ['category' => UserCategory::COLLEGE_TEACHER]);
        $this->assertTrue($collegeTeacher->canBecomeSupervisor());
    }

    /** @test */
    public function when_a_user_is_made_supervisor_cosupervisor_is_also_created()
    {
        $supervisor = create(User::class);

        $supervisor->update(['is_supervisor' => true]);

        $cosupervisors = Cosupervisor::query()
            ->wherePersonType(User::class)
            ->wherePersonId($supervisor->id)
            ->whereIsSupervisor(true)
            ->get();

        $this->assertCount(1, $cosupervisors);
    }

    /** @test */
    public function a_superviosr_has_many_publications()
    {
        $supervisor = create(User::class, [
            'category' => UserCategory::FACULTY_TEACHER,
            'is_supervisor' => true,
        ]);

        $this->assertInstanceOf(MorphMany::class, $supervisor->publications());

        $this->assertCount(0, $supervisor->publications);

        $publication = create(Publication::class, 1, [
            'main_author_type' => User::class,
            'main_author_id' => $supervisor->id,
        ]);

        $this->assertCount(1, $supervisor->fresh()->publications);
    }
}
