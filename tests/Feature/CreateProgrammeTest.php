<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Programme;
use App\Models\ProgrammeRevision;
use App\Models\User;
use App\Types\ProgrammeType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CreateProgrammeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function new_programme_can_be_created()
    {
        $this->signIn();

        $this->withoutExceptionHandling()
            ->get(route('staff.programmes.create'))
            ->assertSuccessful()
            ->assertViewIs('staff.programmes.create')
            ->assertViewHas('types');
    }

    /** @test */
    public function new_programme_can_be_stored_and_its_revisions_also_created()
    {
        $this->signIn();
        $this->withoutExceptionHandling();

        $courses = create(Course::class, 2);

        $courses = array_combine([1, 2], [
            [$courses[0]->id],
            [$courses[1]->id],
        ]);

        $this->post(route('staff.programmes.store'), [
            'code' => 'MCS',
            'wef' => $wef = '2019-08-12',
            'name' => 'M.Sc. Computer Science',
            'type' => ProgrammeType::UNDER_GRADUATE,
            'duration' => 1,
            'semester_courses' => $courses,
        ])->assertRedirect()
        ->assertSessionHasFlash('success', 'Programme created successfully');

        $this->assertEquals(1, Programme::count());
        $programme = Programme::first();

        $this->assertEquals(1, $programme->refresh()->revisions->count());
        $this->assertEquals($wef, $programme->revisions->first()->revised_at->format('Y-m-d'));
        $this->assertEquals(2, $programme->revisions()->first()->courses->count());
        $this->assertEquals(
            [1, $courses[1][0]],
            [(int) $programme->revisions->first()->courses[0]->pivot->semester,
                $programme->revisions->first()->courses[0]->id, ]
        );
        $this->assertEquals(
            [2, $courses[2][0]],
            [(int) $programme->revisions->first()->courses[1]->pivot->semester,
                $programme->revisions->first()->courses[1]->id, ]
        );
    }

    /** @test */
    public function a_programme_with_course_in_common_with_another_programme_can_not_be_created()
    {
        $this->signIn();
        $programme = create(Programme::class);
        $assignedCourse = create(Course::class);
        $unassignedCourse = create(Course::class);

        $revision = create(ProgrammeRevision::class, 1, [
            'programme_id' => $programme->id,
        ]);

        $revision->courses()->attach($assignedCourse, ['semester' => 1]);

        $courses = array_combine([1, 2], [[$assignedCourse->id], [$unassignedCourse->id]]);

        try {
            $this->withoutExceptionHandling()
                ->post(route('staff.programmes.store'), [
                    'code' => 'MCS',
                    'wef' => '2020-01-01',
                    'name' => 'M.C.A. Computer Science',
                    'type' => 'UG',
                    'duration' => 1,
                    'semester_courses' => $courses,
                ]);
            $this->fail('Validation error was expected');
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('semester_courses.1.0', $e->errors());
        }

        $this->assertEquals(1, Programme::count());
    }

    /** @test */
    public function request_validates_type_field_value_can_not_be_other_than_under_graduate_or_post_graduate()
    {
        $this->signIn();

        try {
            $this->withoutExceptionHandling()->post(route('staff.programmes.store'), [
                'code' => 'MCS',
                'wef' => '2020-01-01',
                'name' => 'M.C.A. Computer Science',
                'type' => 'some random type',
                'duration' => 2,
                'courses' => '',
            ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('type', $e->errors());
        }

        $this->assertEquals(Programme::count(), 0);
    }

    /** @test */
    public function request_validates_duration_field_can_not_be_null()
    {
        $this->signIn();

        try {
            $this->withoutExceptionHandling()
                ->post(route('staff.programmes.store'), [
                    'code' => 'MCS',
                    'wef' => '2020-01-01',
                    'name' => 'M.C.A. Computer Science',
                    'type' => 'some random type',
                    'duration' => '',
                    'courses' => '',
                ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('duration', $e->errors());
        }
        $this->assertEquals(Programme::count(), 0);
    }
}
