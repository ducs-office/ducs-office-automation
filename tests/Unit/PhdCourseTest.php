<?php

namespace Tests\Unit;

use App\PhdCourse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhdCourseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function phd_Course_has_core_scope()
    {
        $coreCourses = create(PhdCourse::class, 2, ['type' => 'C']);
        $electiveCourses = create(PhdCourse::class, 2, ['type' => 'E']);

        $this->assertCount(2, PhdCourse::core()->get());
    }
}
