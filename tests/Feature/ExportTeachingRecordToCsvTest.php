<?php

namespace Tests\Feature;

use App\College;
use App\Course;
use App\Programme;
use App\ProgrammeRevision;
use App\Teacher;
use App\TeachingRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
use Tests\TestCase;

class ExportTeachingRecordToCsvTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function all_teaching_records_can_be_downloaded_as_pdf()
    {
        Storage::fake();

        $this->signIn();

        $teacher = create(Teacher::class);
        $college = create(College::class);
        $courses = create(Course::class, 2);
        $programme = create(Programme::class);
        $college->programmes()->attach($programme);
        $programmeRev = create(ProgrammeRevision::class, 1, ['programme_id' => $programme->id]);
        $programmeRev->courses()->sync([
            $courses[0]->id => ['semester' => 1],
            $courses[1]->id => ['semester' => 2],
        ]);

        $oldRecord = create(TeachingRecord::class, 1, [
            'valid_from' => now()->subMonths(8),
            'teacher_id' => $teacher->id,
            'designation' => 'T',
            'college_id' => $college->id,
            'course_id' => $courses[0]->id,
            'semester' => 1,
            'programme_revision_id' => $programmeRev->id,
        ]);

        $newRecord = create(TeachingRecord::class, 1, [
            'valid_from' => now()->subMonths(2),
            'teacher_id' => $teacher->id,
            'designation' => 'T',
            'college_id' => $college->id,
            'course_id' => $courses[1]->id,
            'semester' => 2,
            'programme_revision_id' => $programmeRev->id,
        ]);

        $expectedCSV = implode("\n", [
            'Year,Teacher,Designation,College,Course,Semester,Programme',
            "{$oldRecord->valid_from->year},{$teacher->name},{$oldRecord->getDesignation()},{$college->name},{$courses[0]->name},{$oldRecord->semester},{$programme->name}",
            "{$newRecord->valid_from->year},{$teacher->name},{$newRecord->getDesignation()},{$college->name},{$courses[1]->name},{$newRecord->semester},{$programme->name}",
        ]);

        $response = $this->withoutExceptionHandling()
            ->get(route('staff.teaching_records.export'))
            ->assertSuccessful();

        $this->assertEquals(Storage::path('temp/exports'), $response->baseResponse->getFile()->getPath());

        ob_start();
        $response->sendContent();
        $this->assertEquals($expectedCSV, ob_get_contents());
        ob_end_clean();

        Storage::assertMissing('temp/exports/' . $response->getFile()->getBasename());
    }
}
