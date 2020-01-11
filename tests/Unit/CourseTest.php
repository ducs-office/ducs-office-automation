<?php

namespace Tests\Unit;

use App\Programme;
use App\Course;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        $course = create(Course::class);
        $course->programmes()->attach([$programme->id]);

        $this->assertInstanceOf(BelongsToMany::class, $course->programmes());
        $this->assertInstanceOf(Programme::class, $course->programmes()->first());
        $this->assertEquals($programme->id, $course->programmes()->first()->id);
    }
}
