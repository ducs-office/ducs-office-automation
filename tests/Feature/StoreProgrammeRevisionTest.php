<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Programme;
use App\Models\ProgrammeRevision;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class StoreProgrammeRevisionTest extends TestCase
{
    use RefreshDatabase;

    protected $semester_courses;
    protected $programme;
    protected $revision;

    public function setUp(): void
    {
        parent::setUp();

        $this->programme = create(Programme::class, 1, [
            'duration' => '1',
        ]);

        $this->semester_courses = create(Course::class, 2);
        // semester number starts from 1
        $this->semester_courses = array_combine(
            [1, 2],
            [
                [$this->semester_courses[0]->id],
                [$this->semester_courses[1]->id],
            ]
        );
    }

    /** @test */
    public function revision_of_programme_can_be_stored_with_semester_starting_from_1_not_0()
    {
        $this->withoutExceptionHandling()
            ->signIn();

        $revised_at = '2000-02-01';

        $this->post(route('staff.programmes.revisions.store', $this->programme), [
            'revised_at' => $revised_at,
            'semester_courses' => $this->semester_courses,
        ])
        ->assertRedirect()
        ->assertSessionHasFlash('success', "Programme's revision created successfully!");

        $this->assertEquals(1, Programme::count());
        $this->assertEquals(1, $this->programme->fresh()->revisions->count());
        $this->assertEquals($revised_at, $this->programme->fresh()->revisions->first()->revised_at->format('Y-m-d'));
        $this->assertEquals(2, Programme::first()->revisions()->first()->courses->count());

        $this->assertEquals(
            [1, $this->semester_courses[1][0]],
            [(int) $this->programme->revisions->first()->courses[0]->pivot->semester,
                $this->programme->revisions->first()->courses[0]->id, ]
        );
        $this->assertEquals(
            [2, $this->semester_courses[2][0]],
            [(int) $this->programme->revisions->first()->courses[1]->pivot->semester,
                $this->programme->revisions->first()->courses[1]->id, ]
        );
    }

    /** @test */
    public function request_validates_revised_at_field_is_required()
    {
        $this->signIn();

        $courses = create(Course::class, 2);

        try {
            $this->withoutExceptionHandling()
                ->post(route('staff.programmes.revisions.store', $this->programme), [
                    'semester_courses' => $this->semester_courses,
                ]);
            $this->fail('Validation error was expected. revised_at is required for a programme revision');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('revised_at', $e->errors());
        }

        $this->assertEquals(0, ProgrammeRevision::count());
    }

    /** @test */
    public function request_validates_revised_at_should_not_be_the_same_as_any_existing_revisions_revised_at_that_belong_to_the_same_programme()
    {
        $this->signIn();

        $existingRevision = create(ProgrammeRevision::class, 1, [
            'programme_id' => $this->programme->id,
        ]);

        try {
            $this->withoutExceptionHandling()
                ->post(route('staff.programmes.revisions.store', $this->programme), [
                    'revised_at' => $existingRevision->revised_at->format('Y-m-d'),
                    'semester_courses' => $this->semester_courses,
                ]);
            $this->fail('Validation error was expected');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('revised_at', $e->errors());
        }

        $this->assertEquals(1, $this->programme->revisions()->count());
    }

    /** @test */
    public function request_validates_revised_at_field_is_date()
    {
        $this->signIn();

        $courses = create(Course::class, 2);

        try {
            $this->withoutExceptionHandling()
                ->post(route('staff.programmes.revisions.store', $this->programme), [
                    'revised_at' => 'some random string',
                    'semester_courses' => $this->semester_courses,
                ]);

            $this->fail('Validation error was expected');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('revised_at', $e->errors());
        }

        $this->assertEquals(0, $this->programme->revisions()->count());
    }

    /** @test */
    public function a_programme_revision_with_course_in_common_with_another_programmes_revision_can_not_be_created()
    {
        $this->signIn();

        $programme2 = create(Programme::class, 1, [
            'duration' => 1,
        ]);

        $programme2Revision = create(ProgrammeRevision::class, 1, [
            'programme_id' => $programme2->id,
        ]);

        $programme2RevisionCourse = create(Course::class);

        $programme2Revision->courses()->attach($programme2RevisionCourse, ['semester' => 1]);

        $unassignedCourse = create(Course::class);

        $courses = array_combine([1, 2], [[$programme2RevisionCourse->id], [$unassignedCourse->id]]);

        try {
            $this->withoutExceptionHandling()
                ->post(route('staff.programmes.revisions.store', $this->programme), [
                    'revised_at' => '2021-09-08',
                    'semester_courses' => $courses,
                ]);

            $this->fail('Validation error was expected. revisions of different programmes should not be able to share course(s)');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('semester_courses.1.0', $e->errors());
        }

        $this->assertEquals(0, $this->programme->fresh()->revisions->count());
    }

    /** @test */
    public function revision_of_a_programme_with_courses_common_with_an_existing_revision_of_the_same_programme_can_be_created()
    {
        $this->signIn();

        $existingRevision = create(ProgrammeRevision::class, 1, [
            'programme_id' => $this->programme->id,
        ]);

        $programmeRevisionCourses = create(Course::class, 2);

        $existingRevision->courses()->attach($programmeRevisionCourses[0]->id, ['semester' => 1]);
        $existingRevision->courses()->attach($programmeRevisionCourses[1]->id, ['semester' => 2]);

        // switching the position of  courses
        $courses = array_combine([1, 2], [
            [$programmeRevisionCourses[1]->id],
            [$programmeRevisionCourses[0]->id],
        ]);

        $this->withoutExceptionHandling()
            ->post(route('staff.programmes.revisions.store', $this->programme), [
                'revised_at' => '2019-09-09',
                'semester_courses' => $courses,
            ])->assertRedirect()
            ->assertSessionHasNoErrors()
            ->assertSessionHasFlash('success', "Programme's revision created successfully!");

        $this->assertEquals(2, $this->programme->fresh()->revisions->count());
        $this->assertEquals(
            [1, $courses[1][0]],
            [(int) $this->programme->revisions->find(2)->courses[0]->pivot->semester,
                $this->programme->revisions->find(2)->courses[0]->id, ]
        );
        $this->assertEquals(
            [2, $courses[2][0]],
            [(int) $this->programme->revisions->find(2)->courses[1]->pivot->semester,
                $this->programme->revisions->find(2)->courses[1]->id, ]
        );
    }

    /** @test */
    public function same_courses_cannot_be_assigned_to_different_semesters_of_a_programme_revision()
    {
        $this->signIn();

        $revised_at = '2019-09-08';

        $course = create(Course::class);

        $courses = array_combine([1, 2], [
            [$course->id],
            [$course->id],
        ]);

        try {
            $this->withoutExceptionHandling()
                ->post(route('staff.programmes.revisions.store', $this->programme), [
                    'revised_at' => $revised_at,
                    'semester_courses' => $courses,
                ]);

            $this->fail('Validation error was expected. No two semesters of a programme can have a common course');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('semester_courses.1.0', $e->errors());
        }

        $this->assertEquals(0, $this->programme->fresh()->revisions()->count());
    }
}
