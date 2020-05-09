<?php

namespace Tests\Unit;

use App\Models\User;
use App\Types\UserCategory;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
    public function it_may_have_a_supervisor_profile()
    {
        $user = create(User::class);

        $this->assertInstanceOf(HasOne::class, $user->supervisorProfile());

        $this->assertNull($user->supervisorProfile);

        $profile = $user->supervisorProfile()->create();

        $this->assertTrue($profile->is($user->fresh()->supervisorProfile));
    }

    /** @test */
    public function isSupervisor_method_gives_boolean_indicating_whether_or_not_user_is_a_supervisor()
    {
        $user = create(User::class);

        $this->assertFalse($user->isSupervisor());

        $profile = $user->supervisorProfile()->create();

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
}
