<?php

namespace Tests\Feature;

use App\Course;
use App\ProgrammeRevision;
use App\TeachingRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FilterTeachersTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function teachers_can_be_filtered_based_on_course_taught()
    {
        $this->signIn();

        $programmeRevision = create(ProgrammeRevision::class);
        $algoCourse = create(Course::class, 1, ['name' => 'Algorithms']);
        $dsCourse = create(Course::class, 1, ['name' => 'Data Structure']);
        $programmeRevision->courses()->sync([
            $algoCourse->id => ['semester' => 1],
            $dsCourse->id => ['semester' => 3],
        ]);

        $algoRecords = create(TeachingRecord::class, 2, [
            'programme_revision_id' => $programmeRevision->id,
            'course_id' => $algoCourse->id,
            'semester' => 1,
        ]);

        $dsRecord = create(TeachingRecord::class, 1, [
            'programme_revision_id' => $programmeRevision->id,
            'course_id' => $dsCourse->id,
            'semester' => 3,
        ]);

        $viewTeachers = $this->withoutExceptionHandling()
            ->get(route('staff.teachers.index', [
                'filters' => ['course_id' => $algoCourse->id],
            ]))
            ->assertSuccessful()
            ->assertViewIs('staff.teachers.index')
            ->assertViewHas('teachers')
            ->viewData('teachers');

        $this->assertEquals(2, $viewTeachers->count());
    }

    /** @test */
    public function teachers_who_taught_after_given_date_can_be_filtered()
    {
        $this->signIn();

        $latestRecords = create(TeachingRecord::class, 2, ['valid_from' => now()]);
        $olderRecords = create(TeachingRecord::class, 2, ['valid_from' => now()->subYear(1)]);

        $viewTeachers = $this->withoutExceptionHandling()
            ->get(route('staff.teachers.index', [
                'filters' => [
                    'valid_from' => now()->subMonths(6)->format('Y-m-d'),
                ],
            ]))
            ->assertSuccessful()
            ->assertViewIs('staff.teachers.index')
            ->assertViewHas('teachers')
            ->viewData('teachers');

        $this->assertCount(2, $viewTeachers);
        $this->assertEquals(
            $viewTeachers->pluck('id')->toArray(),
            $latestRecords->pluck('teacher_id')->toArray()
        );
    }
}
