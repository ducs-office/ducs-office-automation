<?php

namespace Tests\Unit;

use App\CollegeTeacher;
use PHPUnit\Framework\TestCase;

class CollegeTeacherTest extends TestCase
{
    public function test_it_gives_full_name()
    {
        $teacher = new CollegeTeacher();
        $teacher->first_name = 'John';
        $teacher->last_name = 'Doe';

        $this->assertEquals('John Doe', $teacher->name);
    }
}
