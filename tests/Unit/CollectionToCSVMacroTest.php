<?php

namespace Tests\Unit;

use App\Models\College;
use App\Models\Course;
use App\Models\CourseProgrammeRevision;
use App\Models\PastTeachersProfile;
use App\Models\Programme;
use App\Models\Teacher;
use App\Models\TeachingRecord;
use App\Types\CourseType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CollectionToCSVMacroTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_convert_eloquent_collection_to_csv_content()
    {
        $deenDayalCollege = create(College::class, 1, ['name' => 'Deen Dayal Upadhyay College']);
        $bscHonoursLatestRev = create(Programme::class, 1, ['name' => 'BSC (H) Computer Science'])
            ->revisions()->create(['revised_at' => now()->subMonths(6)]);

        $javaCourse = create(Course::class, 1, ['name' => 'Programming in Java', 'type' => CourseType::CORE]);
        $phpCourse = create(Course::class, 1, ['name' => 'PHP Programming', 'type' => CourseType::GENERAL_ELECTIVE]);

        $bscHonoursLatestRev->courses()->sync([
            $javaCourse->id => ['semester' => 1],
            $phpCourse->id => ['semester' => 3],
        ]);
        $ankit = create(Teacher::class, 1, ['first_name' => 'Ankit', 'last_name' => 'Rajpal']);
        $ankitRecord = create(TeachingRecord::class, 1, [
            'teacher_id' => $ankit->id,
            'course_id' => $phpCourse->id,
            'programme_revision_id' => $bscHonoursLatestRev->id,
            'semester' => 3,
            'college_id' => $deenDayalCollege->id,
        ]);

        $rajan = create(Teacher::class, 1, ['first_name' => 'Rajan', 'last_name' => 'Gupta']);
        $rajanRecord = create(TeachingRecord::class, 1, [
            'teacher_id' => $rajan->id,
            'course_id' => $javaCourse->id,
            'programme_revision_id' => $bscHonoursLatestRev->id,
            'semester' => 1,
            'college_id' => $deenDayalCollege->id,
        ]);

        $csv = TeachingRecord::with([
            'teacher',
            'college',
            'course',
            'programmeRevision.programme',
        ])
        ->get()->toCsv([
            'Teacher' => 'teacher.name',
            'College' => 'college.name',
            'Course' => 'course.name',
            'Programme' => 'programmeRevision.programme.name',
        ]);
        $expectedCSV = implode("\n", [
            'Teacher,College,Course,Programme',
            "{$ankit->name},{$deenDayalCollege->name},{$phpCourse->name},{$bscHonoursLatestRev->programme->name}",
            "{$rajan->name},{$deenDayalCollege->name},{$javaCourse->name},{$bscHonoursLatestRev->programme->name}",
        ]);

        $this->assertEquals($expectedCSV, $csv);
    }
}
