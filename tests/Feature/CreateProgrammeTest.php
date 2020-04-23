<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Programme;
use App\Models\User;
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
    public function new_programme_can_be_stored()
    {
        $this->signIn();
        $this->withoutExceptionHandling();
        $this->post(route('staff.programmes.store'), [
            'code' => 'MCS',
            'wef' => '2019-08-12',
            'name' => 'M.Sc. Computer Science',
            'type' => 'UG',
            'duration' => 1,
            'semester_courses' => [
                [create(Course::class)->id],
                [create(Course::class)->id],
            ],
        ])->assertRedirect()
        ->assertSessionHasFlash('success', 'Programme created successfully');

        $this->assertEquals(1, Programme::count());
        $this->assertEquals(2, Programme::first()->revisions()->first()->courses->count());
    }

    /** @test */
    public function courses_cannot_be_assigned_to_multiple_programmes()
    {
        $this->signIn();
        $programme = create(Programme::class);
        $assignedCourse = create(Course::class);
        $unassignedCourse = create(Course::class);

        $programmeRevision = $programme->revisions()->create(['revised_at' => $programme->wef]);
        $programmeRevision->courses()->attach($assignedCourse, ['semester' => 1]);

        try {
            $this->withoutExceptionHandling()
                ->post(route('staff.programmes.store'), [
                    'code' => 'MCS',
                    'wef' => '2020-01-01',
                    'name' => 'M.C.A. Computer Science',
                    'type' => 'UG',
                    'duration' => 1,
                    'semester_courses' => [
                        [$assignedCourse->id],
                        [$unassignedCourse->id],
                    ],
                ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('semester_courses.0.0', $e->errors());
        }

        $this->assertEquals(1, Programme::count());

        $anotherUnassignedCourse = create(Course::class);

        $this->withoutExceptionHandling()
            ->post(route('staff.programmes.store'), [
                'code' => 'MCS',
                'wef' => '2020-01-01',
                'name' => 'M.C.A. Computer Science',
                'type' => 'UG',
                'duration' => 1,
                'semester_courses' => [
                    [$anotherUnassignedCourse->id],
                    [$unassignedCourse->id],
                ],
            ])->assertRedirect()
            ->assertSessionHasFlash('success', 'Programme created successfully');

        $this->assertEquals(2, Programme::count());
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
