<?php

namespace Tests\Feature;

use App\College;
use App\Course;
use App\ProgrammeRevision;
use App\Teacher;
use App\TeachingRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeachersProfileTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function teacher_can_view_their_profiles()
    {
        $this->signInTeacher($teacher = create(Teacher::class));

        $response = $this->withoutExceptionHandling()
            ->get(route('teachers.profile'))
            ->assertSuccessful()
            ->assertViewHasAll([
                'teacher',
                'designations',
            ])
            ->assertSee($teacher->name);
    }

    /** @test */
    public function teaching_records_of_a_teacher_can_be_viewed()
    {
        $this->signInTeacher($teacher = create(Teacher::class));

        $records = create(TeachingRecord::class, 3, ['teacher_id' => $teacher->id]);

        $viewTeacher = $this->withoutExceptionHandling()
            ->get(route('teachers.profile'))
            ->assertSuccessful()
            ->viewData('teacher');

        $this->assertCount(3, $viewTeacher->teachingRecords);
    }
}
