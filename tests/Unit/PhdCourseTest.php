<?php

namespace Tests\Unit;

use App\Models\PhdCourse;
use App\Types\PrePhdCourseType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhdCourseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function phd_Course_has_core_scope()
    {
        $coreCourses = create(PhdCourse::class, 2, ['type' => PrePhdCourseType::CORE]);
        $electiveCourses = create(PhdCourse::class, 2, ['type' => PrePhdCourseType::ELECTIVE]);

        $this->assertCount(2, PhdCourse::core()->get());
    }
}
