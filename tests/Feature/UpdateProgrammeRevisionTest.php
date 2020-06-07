<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Programme;
use App\Models\ProgrammeRevision;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UpdateProgrammeRevisionTest extends TestCase
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

        $this->revision = create(ProgrammeRevision::class, 1, [
            'programme_id' => $this->programme->id,
        ]);

        $this->semester_courses = create(Course::class, 2);

        collect($this->semester_courses)->map(function ($course, $index) {
            $course->programmeRevisions()->attach($this->revision, [
                'semester' => $index + 1,
            ]);
        });
    }

    /** @test */
    public function revision_of_programme_can_be_updated()
    {
        $this->withoutExceptionHandling()
            ->signIn();

        $revised_at = '2000-02-01';

        $courses = create(Course::class, 2);
        $updateCourses = array_combine([1, 2], [
            [$courses[0]->id],
            [$courses[1]->id],
        ]);

        $this->patch(
            route('staff.programmes.revisions.update', [
                'programme' => $this->programme,
                'revision' => $this->revision,
            ]),
            [
                'revised_at' => $revised_at,
                'semester_courses' => $updateCourses,
            ]
        )
            ->assertRedirect()
            ->assertSessionHasFlash('success', "Programme's revision edited successfully!");

        $this->assertEquals(1, ProgrammeRevision::count());
        $this->assertEquals($revised_at, $this->programme->refresh()->revisions->first()->revised_at->format('Y-m-d'));
        $this->assertEquals(
            [1, $updateCourses[1][0]],
            [(int) $this->programme->revisions->first()->courses[0]->pivot->semester,
                $this->programme->revisions->first()->courses[0]->id, ]
        );
        $this->assertEquals(
            [2, $updateCourses[2][0]],
            [(int) $this->programme->revisions->first()->courses[1]->pivot->semester,
                $this->programme->revisions->first()->courses[1]->id, ]
        );
    }

    /** @test */
    public function request_validates_revised_at_field_cannot_be_null()
    {
        $this->signIn();

        try {
            $this->withoutExceptionHandling()
                ->patch(
                    route('staff.programmes.revisions.update', [
                        'programme' => $this->programme,
                        'revision' => $this->revision,
                    ]),
                    [
                        'revised_at' => '',
                    ]
                );
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('revised_at', $e->errors());
        }

        $this->assertEquals(1, ProgrammeRevision::count());
        $this->assertEquals($this->revision->revised_at->format('Y-m-d'), $this->revision->fresh()->revised_at->format('Y-m-d'));
    }

    /** @test */
    public function request_validates_revised_at_should_not_be_the_same_as_any_existing_revisions_revised_at_that_belong_to_the_same_programme()
    {
        $this->signIn();

        $revision2 = create(ProgrammeRevision::class, 1, [
            'programme_id' => $this->programme->id,
        ]);

        try {
            $this->withoutExceptionHandling()
                ->patch(
                    route('staff.programmes.revisions.update', [
                        'programme' => $this->programme,
                        'revision' => $this->revision,
                    ]),
                    [
                        'revised_at' => $revision2->revised_at->format('Y-m-d'),
                    ]
                );
            $this->fail('Validation error was expected. Any two revisions of a programme can not have the same revised_at date');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('revised_at', $e->errors());
        }

        $this->assertEquals(2, ProgrammeRevision::count());
        $this->assertEquals($this->revision->revised_at->format('Y-m-d'), $this->revision->fresh()->revised_at->format('Y-m-d'));
    }

    /** @test */
    public function request_validates_revised_at_field_is_a_date()
    {
        $this->signIn();

        try {
            $this->withoutExceptionHandling()
                ->patch(route('staff.programmes.revisions.update', [
                    'programme' => $this->programme,
                    'revision' => $this->revision,
                ]), [
                    'revised_at' => 'some random string',
                ]);
            $this->fail('Validation error was expected');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('revised_at', $e->errors());
        }

        $this->assertEquals($this->revision->revised_at->format('Y-m-d'), $this->revision->fresh()->revised_at->format('Y-m-d'));
    }

    /** @test */
    public function courses_already_assigned_to_a_programmes_revision_can_not_be_assigned_to_any_other_programmes_revision()
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

        $updateCourses = array_combine([1, 2], [
            [$this->semester_courses[1][0]], // this course belongs to another programmes revision
            [$programme2RevisionCourse->id],
        ]);

        try {
            $this->withoutExceptionHandling()
                ->patch(route('staff.programmes.revisions.update', [
                    'programme' => $this->programme,
                    'revision' => $this->revision,
                ]), [
                    'semester_courses' => $updateCourses,
                ]);
            $this->fail('Validation error was expected. revisions of different programmes should not be able to share course(s)');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('semester_courses.1.0', $e->errors());
        }
    }

    /** @test */
    public function assigned_courses_can_be_moved_to_other_semester_of_a_programmes_revision()
    {
        $this->signIn();

        // interchange the semester courses,
        $updateCourses = array_combine([1, 2], [
            [$this->semester_courses[1]->id],
            [$this->semester_courses[0]->id],
        ]);

        $this->withoutExceptionHandling()
            ->patch(route('staff.programmes.revisions.update', [
                'programme' => $this->programme,
                'revision' => $this->revision,
            ]), [
                'revised_at' => '2019-09-09',
                'semester_courses' => $updateCourses,
            ])->assertRedirect()
            ->assertSessionHasNoErrors()
            ->assertSessionHasFlash('success', "Programme's revision edited successfully!");

        $this->assertEquals(
            [1, $updateCourses[1][0]],
            [(int) $this->programme->revisions->first()->courses[0]->pivot->semester,
                $this->programme->revisions->first()->courses[0]->id, ]
        );
        $this->assertEquals(
            [2, $updateCourses[2][0]],
            [(int) $this->programme->revisions->first()->courses[1]->pivot->semester,
                $this->programme->revisions->first()->courses[1]->id, ]
        );
    }

    /** @test */
    public function same_courses_cannot_be_assigned_to_different_semesters_of_a_programme_revision()
    {
        $this->signIn();

        $course = create(Course::class);

        $updateCourses = array_combine([1, 2], [
            [$course->id],
            [$course->id],
        ]);

        try {
            $this->withoutExceptionHandling()
            ->patch(route('staff.programmes.revisions.update', [
                'programme' => $this->programme,
                'revision' => $this->revision,
            ]), [
                'semester_courses' => $updateCourses,
            ]);

            $this->fail('Validation error was expected. No two semesters of a programme can have a common course');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('semester_courses.1.0', $e->errors());
        }
    }
}
