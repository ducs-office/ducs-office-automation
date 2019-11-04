<?php

namespace Tests\Unit;

use App\Programme;
use App\Course;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function course_belongs_to_a_programme()
    {
        $programme = create(Programme::class);
        $course = create(Course::class, 1, ['programme_id' => $programme->id]);

        $this->assertInstanceOf(BelongsTo::class, $course->programme());
        $this->assertInstanceOf(Programme::class, $course->programme);
        $this->assertEquals($programme->id, $course->programme->id);
    }
}
