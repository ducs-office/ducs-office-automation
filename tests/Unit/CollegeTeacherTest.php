<?php

namespace Tests\Unit;

use App\Teacher;
use PHPUnit\Framework\TestCase;

class TeacherTest extends TestCase
{
    public function test_it_gives_full_name()
    {
        $teacher = new Teacher();
        $teacher->first_name = 'John';
        $teacher->last_name = 'Doe';

        $this->assertEquals('John Doe', $teacher->name);
    }
}
