<?php

namespace Tests\Feature;

use App\Programme;
use App\Course;
use App\User;
// use Dotenv\Exception\ValidationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

class CreateProgrammeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_create_new_programme()
    {
        $this->signIn();
        $this->withoutExceptionHandling();
        $this->post('/programmes', [
            'code' => 'MCS',
            'wef' => '2019-08-12',
            'name' => 'M.Sc. Computer Science',
            'type' => 'Under Graduate(U.G.)',
            'duration' => 2,
        ])->assertRedirect('/programmes')
        ->assertSessionHasFlash('success', 'Programme created successfully');

        $this->assertEquals(1, Programme::count());
    }

    /** @test */
    public function admin_can_only_assign_not_assigned_courses_to_programme()
    {
        $this->signIn();
        $programme = create(Programme::class);
        $course = create(Course::class, 1, ['programme_id' => $programme->id]);

        try {
            $this->withoutExceptionHandling()
                ->post('/programmes', [
                'code' => 'MCS',
                'wef' => '2020-01-01',
                'name' => 'M.C.A. Computer Science',
                'type' => 'Under Graduate(U.G.)',
                'duration' => 2,
                'courses' => [$course->id],
            ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('courses.0', $e->errors());
        }
        $this->assertEquals(Programme::count(), 1);

        $course = create(Course::class, 1, ['programme_id' => null]);
        
        $this->withoutExceptionHandling()
                ->post('/programmes', [
                'code' => 'MCS',
                'wef' => '2020-01-01',
                'name' => 'M.C.A. Computer Science',
                'type' => 'Under Graduate(U.G.)',
                'duration' => 2,
                'courses' => [$course->id],
            ])->assertRedirect('/programmes')
            ->assertSessionHasFlash('success', 'Programme created successfully');
        $this->assertEquals(Programme::count(), 2);
    }

    /** @test */
    public function request_validates_type_field_value_can_not_be_other_than_under_graduate_or_post_graduate()
    {
        $this->signIn();

        try {
            $this->withoutExceptionHandling()
                ->post('/programmes', [
                'code' => 'MCS',
                'wef' => '2020-01-01',
                'name' => 'M.C.A. Computer Science',
                'type' => 'some random type',
                'duration' => 2,
                'courses' => ''
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
                ->post('/programmes', [
                'code' => 'MCS',
                'wef' => '2020-01-01',
                'name' => 'M.C.A. Computer Science',
                'type' => 'some random type',
                'duration' => '',
                'courses' => ''
            ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('duration', $e->errors());
        }
        $this->assertEquals(Programme::count(), 0);
    }
}
