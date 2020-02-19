<?php

namespace Tests\Unit;

use App\College;
use App\Course;
use App\CourseProgrammeRevision;
use App\PastTeachersProfile;
use App\Programme;
use App\Teacher;
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

        $algorithmCourse = create(Course::class, 1, ['name' => 'Design & Analysis of Algorithms', 'type' => 'C' ]);
        $javaCourse = create(Course::class, 1, ['name' => 'Programming in Java', 'type' => 'C']);
        $phpCourse = create(Course::class, 1, ['name' => 'PHP Programming', 'type' => 'GE']);
        $itCourse = create(Course::class, 1, ['name' => 'Internet Technologies', 'type' => 'OE']);

        $bscHonoursLatestRev->courses()->sync([
            "{$algorithmCourse->id}" => [ 'semester' => 1 ],
            "{$javaCourse->id}" => [ 'semester' => 1 ],
            "{$phpCourse->id}" => [ 'semester' => 3 ],
            "{$itCourse->id}" => [ 'semester' => 3 ],
        ]);
        $ankit = create(Teacher::class, 1, ['first_name' => 'Ankit', 'last_name' => 'Rajpal']);
        $ankitProfile = create(PastTeachersProfile::class, 1, [
            'teacher_id' => $ankit->id,
            'college_id' => $deenDayalCollege->id,
            'designation' => 'P',
        ]);
        $ankitProfile->past_teaching_details()->sync([
            CourseProgrammeRevision::whereCourseId($algorithmCourse->id)->first()->id,
            CourseProgrammeRevision::whereCourseId($javaCourse->id)->first()->id,
        ]);

        $rajan = create(Teacher::class, 1, ['first_name' => 'Rajan', 'last_name' => 'Gupta']);
        $rajanProfile = create(PastTeachersProfile::class, 1, [
            'teacher_id' => $rajan->id,
            'college_id' => $deenDayalCollege->id,
            'designation' => 'T'
        ]);
        $rajanProfile->past_teaching_details()->sync([
            CourseProgrammeRevision::whereCourseId($itCourse->id)->first()->id,
            CourseProgrammeRevision::whereCourseId($phpCourse->id)->first()->id,
        ]);

        $csv = PastTeachersProfile::with([
            'teacher',
            'college',
            'past_teaching_details.course',
            'past_teaching_details.programme_revision.programme',
        ])
        ->get()->toCsv([
            'Teacher' => 'teacher.name',
            'College' => 'college.name',
            'Course' => 'past_teaching_details.*.course.name',
            'Programme' => 'past_teaching_details.*.programme_revision.programme.name',
        ]);
        $expectedCSV = implode("\n", [
            "Teacher,College,Course,Programme",
            "{$ankit->name},{$deenDayalCollege->name},{$algorithmCourse->name},{$bscHonoursLatestRev->programme->name}",
            "{$ankit->name},{$deenDayalCollege->name},{$javaCourse->name},{$bscHonoursLatestRev->programme->name}",
            "{$rajan->name},{$deenDayalCollege->name},{$itCourse->name},{$bscHonoursLatestRev->programme->name}",
            "{$rajan->name},{$deenDayalCollege->name},{$phpCourse->name},{$bscHonoursLatestRev->programme->name}",
        ]);

        $this->assertEquals($expectedCSV, $csv);
    }
}
