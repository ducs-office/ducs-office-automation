<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\ProgrammeRevision;
use App\Models\TeachingRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ViewTeachingRecordsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function all_teaching_records_can_be_viewed_by_staff_users()
    {
        $this->signIn();

        $records = create(TeachingRecord::class, 4);

        $viewRecords = $this->withoutExceptionHandling()
            ->get(route('staff.teaching_records.index'))
            ->assertSuccessful()
            ->assertViewIs('staff.teaching_records.index')
            ->assertViewHas(['records'])
            ->viewData('records');

        $this->assertEquals(4, $viewRecords->count());
        $this->assertEquals($records->sortByDesc('valid_from')->pluck('teacher.id'), $viewRecords->pluck('teacher.id'));
        $this->assertEquals($records->sortByDesc('valid_from')->pluck('college.id'), $viewRecords->pluck('college.id'));
    }

    /** @test */
    public function all_courses_are_sent_to_the_view()
    {
        $this->signIn();

        $records = create(TeachingRecord::class, 4);

        $viewCourses = $this->withoutExceptionHandling()
            ->get(route('staff.teaching_records.index'))
            ->assertSuccessful()
            ->assertViewIs('staff.teaching_records.index')
            ->assertViewHas('courses')
            ->viewData('courses');

        $this->assertInstanceOf(Collection::class, $viewCourses);
        $this->assertEquals(Course::all()->pluck('id'), $viewCourses->pluck('id'));
        $this->assertEquals(Course::all()->pluck('code'), $viewCourses->pluck('code'));
        $this->assertEquals(Course::all()->pluck('name'), $viewCourses->pluck('name'));
    }

    /** @test */
    public function teaching_records_can_be_filtered_based_on_course_taught()
    {
        $this->signIn();

        $programmeRevision = create(ProgrammeRevision::class);
        $algorithmCourse = create(Course::class, 1, ['name' => 'Algorithms']);
        $dsCourse = create(Course::class, 1, ['name' => 'Data Structure']);
        $programmeRevision->courses()->sync([
            $algorithmCourse->id => ['semester' => 1],
            $dsCourse->id => ['semester' => 3],
        ]);

        $algorithmRecords = create(TeachingRecord::class, 2, [
            'programme_revision_id' => $programmeRevision->id,
            'course_id' => $algorithmCourse->id,
            'semester' => 1,
        ]);

        $dsRecord = create(TeachingRecord::class, 1, [
            'programme_revision_id' => $programmeRevision->id,
            'course_id' => $dsCourse->id,
            'semester' => 3,
        ]);

        $viewRecords = $this->withoutExceptionHandling()
            ->get(route('staff.teaching_records.index', [
                'filters' => ['course_id' => $algorithmCourse->id],
            ]))
            ->assertSuccessful()
            ->assertViewIs('staff.teaching_records.index')
            ->assertViewHas('records')
            ->viewData('records');

        $this->assertEquals(2, $viewRecords->count());
    }

    /** @test */
    public function teachers_who_taught_after_given_date_can_be_filtered()
    {
        $this->signIn();

        $latestRecords = create(TeachingRecord::class, 2, ['valid_from' => now()]);
        $olderRecords = create(TeachingRecord::class, 2, ['valid_from' => now()->subYear(1)]);

        $viewRecords = $this->withoutExceptionHandling()
            ->get(route('staff.teaching_records.index', [
                'filters' => [
                    'valid_from' => now()->subMonths(6)->format('Y-m-d'),
                ],
            ]))
            ->assertSuccessful()
            ->assertViewIs('staff.teaching_records.index')
            ->assertViewHas('records')
            ->viewData('records');

        $this->assertCount(2, $viewRecords);
        $this->assertEquals($viewRecords->pluck('teacher.id'), $latestRecords->pluck('teacher.id'));
        $this->assertEquals($viewRecords->pluck('valid_from'), $latestRecords->pluck('valid_from'));
    }

    /** @test */
    public function teachering_records_are_in_reverse_chronological_order()
    {
        $this->signIn();

        $olderRecord = create(TeachingRecord::class, 1, ['valid_from' => now()->subYears(1)]);
        $latestRecord = create(TeachingRecord::class, 1, ['valid_from' => now()]);
        $midRecord = create(TeachingRecord::class, 1, ['valid_from' => now()->subMonths(6)]);

        $viewRecords = $this->withoutExceptionHandling()
            ->get(route('staff.teaching_records.index'))
            ->assertSuccessful()
            ->assertViewIs('staff.teaching_records.index')
            ->assertViewHas('records')
            ->viewData('records');

        $this->assertEquals(
            [$latestRecord->id, $midRecord->id, $olderRecord->id],
            $viewRecords->pluck('id')->toArray(),
            'records are not in reversed chronological order'
        );
    }
}
