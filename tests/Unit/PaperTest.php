<?php

namespace Tests\Unit;

use App\Course;
use App\Paper;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaperTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function paper_belongs_to_a_course()
    {
        $course = create(Course::class);
        $paper = create(Paper::class, 1, ['course_id' => $course->id]);

        $this->assertInstanceOf(BelongsTo::class, $paper->course());
        $this->assertInstanceOf(Course::class, $paper->course);
        $this->assertEquals($course->id, $paper->course->id);
    }
}
