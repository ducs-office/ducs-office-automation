<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Course;
use App\Programme;
use App\ProgrammeRevision;
use Illuminate\Validation\ValidationException;

class StoreProgrammeRevisionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function revision_of_programme_can_be_stored()
    {
        $this->withoutExceptionHandling()
            ->signIn();

        $programme = create(Programme::class, 1, ['wef' => '1973-02-08', 'duration' => '1']);
        $revision = create(ProgrammeRevision::class, 1, ['revised_at' => $programme->wef, 'programme_id' => $programme->id]);
        $semester_courses = create(Course::class, 2);

        foreach ($semester_courses as $index => $course) {
            $course->programme_revisions()->attach($revision, ['semester' => $index + 1]);
        }

        $revised_at = '2000-02-01';

        $this->post(route('staff.programmes.revisions.store', $programme), [
            'revised_at' => $revised_at,
            'semester_courses' => [
                [$semester_courses[0]->id],
                [$semester_courses[1]->id]
            ]
        ])
        ->assertRedirect()
        ->assertSessionHasFlash('success', "Programme's revision created successfully!");

        $this->assertEquals(1, Programme::count());
        $this->assertEquals(2, $programme->fresh()->revisions->count());
        $this->assertEquals($revised_at, $programme->fresh()->revisions()->find(2)->revised_at->format('Y-m-d'));
    }

    /** @test */
    public function request_validates_revised_at_field_is_required()
    {
        $this->signIn();

        $programme = create(Programme::class, 1, ['duration' => 1, 'wef' => "2000-01-09"]);
        $revision = create(ProgrammeRevision::class, 1, ['revised_at' => $programme->wef, 'programme_id' => $programme->id]);
        $semester_courses = create(Course::class, 2);

        foreach ($semester_courses as $index => $course) {
            $course->programme_revisions()->attach($revision, ['semester' => $index + 1]);
        }

        try {
            $this->withoutExceptionHandling()
                ->post(
                    route("staff.programmes.revisions.store", $programme),
                    [
                        'semester_courses' => [[$semester_courses[0]->id], [$semester_courses[1]->id]]
                    ]
                );
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('revised_at', $e->errors());
        }

        $this->assertEquals(1, ProgrammeRevision::count());
        $this->assertEquals('2000-01-09', $revision->fresh()->revised_at->format('Y-m-d'));
    }

    /** @test */
    public function request_validates_revised_at_field_is_unique()
    {
        $this->signIn();

        $programme = create(Programme::class, 1, ['duration' => 1, 'wef' => "2000-01-09"]);
        $revision = create(ProgrammeRevision::class, 1, ['revised_at' => $programme->wef, 'programme_id' => $programme->id]);
        $semester_courses = create(Course::class, 3);

        foreach ($semester_courses as $index => $course) {
            $course->programme_revisions()->attach($revision, ['semester' => $index + 1]);
        }

        try {
            $this->withoutExceptionHandling()
                ->post(
                    route("staff.programmes.revisions.store", $programme),
                    [
                        'revised_at' => $programme->wef->format('Y-m-d'),
                        'semester_courses' => [[$semester_courses[0]->id], [$semester_courses[1]->id]]
                    ]
                );
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('revised_at', $e->errors());
        }

        $this->assertEquals(1, ProgrammeRevision::count());
        $this->assertEquals(3, $revision->fresh()->courses()->count());
    }

    /** @test */
    public function request_validates_revised_at_field_is_date()
    {
        $this->signIn();

        $programme = create(Programme::class, 1, ['duration' => 1, 'wef' => "2000-01-09"]);
        $revision = create(ProgrammeRevision::class, 1, ['revised_at' => $programme->wef, 'programme_id' => $programme->id]);
        $courses = create(Course::class, 2);

        foreach ($courses as $index => $course) {
            $course->programme_revisions()->attach($revision, ['semester' => $index + 1]);
        }

        try {
            $this->withoutExceptionHandling()
                ->post(route("staff.programmes.revisions.store", $programme), [
                        'revised_at' => 'some random string',
                        'semester_courses' => [[$courses[0]->id], [$courses[1]->id]]
                    ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('revised_at', $e->errors());
        }

        $this->assertEquals(1, ProgrammeRevision::count());
        $this->assertEquals($programme->wef, Programme::find(1)->revisions->max('revised_at'));

        $revised_at = "2019-09-08";

        $this->withoutExceptionHandling()
        ->post(route("staff.programmes.revisions.store", $programme), [
            'revised_at' => $revised_at,
            'semester_courses' => [[$courses[0]->id], [$courses[1]->id]],
        ]);

        $this->assertEquals($revised_at, date('Y-m-d', strtotime(Programme::find(1)->revisions()->max('revised_at'))));
    }

    /** @test */
    public function wef_field_of_proramme_updates_when_revised_on_field_updates()
    {
        $this->signIn();

        $programme = create(Programme::class, 1, ['duration' => 1, 'wef' => "2000-01-09"]);
        $revision = create(ProgrammeRevision::class, 1, ['revised_at' => $programme->wef, 'programme_id' => $programme->id]);
        $courses = create(Course::class, 2);

        foreach ($courses as $index => $course) {
            $course->programme_revisions()->attach($revision, ['semester' => $index + 1]);
        }


        $revised_at = "2019-09-08";

        $this->withoutExceptionHandling()
        ->post(route("staff.programmes.revisions.store", $programme), [
            'revised_at' => $revised_at,
            'semester_courses' => [[$courses[0]->id], [$courses[1]->id]],
        ]);

        $this->assertEquals($revised_at, Programme::find(1)->wef->format('Y-m-d'));
    }

    /** @test */
    public function assigned_courses_can_not_be_assigned_to_the_programme()
    {
        $this->signIn();

        $assignedCourse = create(Course::class);
        $programme1 = create(Programme::class, 1, ['wef' => '1999-09-08', 'duration' => '1']);
        $revision1 = create(ProgrammeRevision::class, 1, ['revised_at' => $programme1->wef, 'programme_id' => $programme1->id]);
        $assignedCourse->programme_revisions()->attach($revision1, ['semester' => 1]);
        $unassignedCourses = create(Course::class, 2);

        $programme2 = create(Programme::class, 1, ['duration' => 1, 'wef' => '2000-09-08']);
        $revision2 = create(ProgrammeRevision::class, 1, ['revised_at' => $programme2->wef, 'programme_id' => $programme2->id]);
        foreach ($unassignedCourses as $index => $course) {
            $course->programme_revisions()->attach($revision2, ['semester' => $index + 1]);
        }

        try {
            $this->withoutExceptionHandling()
                ->post(route('staff.programmes.revisions.store', $programme2), [
                    'revised_at' => $revised_at = "2021-09-08",
                    'semester_courses' => [
                        [$assignedCourse->id],
                        [$unassignedCourses[0]->id]
                    ],
                ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('semester_courses.0.0', $e->errors());
        }

        $this->assertEquals(1, $programme2->fresh()->revisions->count());
        $this->assertEquals('2000-09-08', $programme2->fresh()->revisions()->first()->revised_at->format('Y-m-d'));
    }

    /** @test */
    public function assigned_courses_can_be_moved_to_other_semester_of_the_programme()
    {
        $this->signIn();

        $programme = create(Programme::class, 1, ['wef' => '2000-10-10', 'duration' => 1]);
        $courses = create(Course::class, 3);
        $revision = create(ProgrammeRevision::class, 1, ['revised_at' => $programme->wef, 'programme_id' => $programme->id]);

        $courses[1]->programme_revisions()->attach($revision, ['semester' => 1]);

        $this->withoutExceptionHandling()
            ->post(route('staff.programmes.revisions.store', $programme), [
                'revised_at' => '2019-09-09',
                'semester_courses' => [
                    1 => [$courses[0]->id, $courses[1]->id],
                    2 => [$courses[2]->id],
                ]
            ])->assertRedirect()
            ->assertSessionHasNoErrors()
            ->assertSessionHasFlash('success', "Programme's revision created successfully!");

        $this->assertEquals(2, $programme->fresh()->revisions()->find(2)->courses()->wherePivot('semester', 1)->count());
        $this->assertEquals(1, $programme->fresh()->revisions()->find(1)->courses()->wherePivot('semester', 1)->count());
    }

    /** @test */
    public function same_courses_cannot_be_assigned_to_different_semesters_of_programme()
    {
        $this->signIn();

        $programme = create(Programme::class, 1, ['wef' => '2000-09-09', 'duration' => '1']);
        $courses = create(course::class, 2);
        $revision = create(ProgrammeRevision::class, 1, ['revised_at' => $programme->wef, 'programme_id' => $programme->id]);

        foreach ($courses as $index => $course) {
            $course->programme_revisions()->attach($revision, ['semester' => $index + 1]);
        }

        $revised_at = "2019-09-08";

        try {
            $this->withoutExceptionHandling()
            ->post(route("staff.programmes.revisions.store", $programme), [
                'revised_at' => $revised_at,
                'semester_courses' => [[$courses[0]->id], [$courses[1]->id, $courses[0]->id]],
            ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('semester_courses.1.1', $e->errors());
        }

        $this->assertEquals(1, $programme->fresh()->revisions()->count());
    }
}
