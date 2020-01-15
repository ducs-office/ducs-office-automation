<?php

namespace Tests\Unit;

use App\Programme;
use App\Course;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

        $this->assertInstanceOf(BelongsToMany::class, $course->programmes());

        $course->programmes()->attach([$programme->id], ['semester' => 1]);

        $this->assertInstanceOf(Programme::class, $course->programmes()->first());
        $this->assertEquals($programme->id, $course->programmes()->first()->id);
    }

    /** @test */
    public function a_course_has_many_revisions()
    {
        $programme = create(Programme::class);
        $course = create(Course::class);

        $this->assertInstanceOf(HasMany::class, $course->revisions());

        $course->revisions()->createMany([
            [ 'revised_at' => now()->subYears(1) ],
            [ 'revised_at' => now()->subYears(2) ],
            [ 'revised_at' => now()->subYears(4) ],
        ]);

        $this->assertCount(3, $course->revisions);
    }
}
