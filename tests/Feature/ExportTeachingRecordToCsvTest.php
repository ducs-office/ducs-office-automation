<?php

namespace Tests\Feature;

use App\Models\College;
use App\Models\Course;
use App\Models\Programme;
use App\Models\ProgrammeRevision;
use App\Models\Teacher;
use App\Models\TeachingRecord;
use App\Models\User;
use App\Types\TeacherStatus;
use App\Types\UserCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
use Tests\TestCase;

class ExportTeachingRecordToCsvTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function all_teaching_records_can_be_downloaded_as_csv()
    {
        Storage::fake();

        $this->signIn();

        $teacher = create(User::class, 1, [
            'category' => UserCategory::COLLEGE_TEACHER,
        ]);
        $records = create(TeachingRecord::class, 3, ['teacher_id' => $teacher->id]);

        $expectedCSV = $records->sortByDesc('valid_from')
            ->map(function ($record) {
                return implode(',', [
                    $record->valid_from->year,
                    $record->teacher->name,
                    $record->status,
                    $record->designation,
                    $record->college->name,
                    $record->course->name,
                    $record->semester,
                    $record->programmeRevision->programme->name,
                ]);
            })->prepend(
                implode(',', ['Year', 'Teacher', 'Status', 'Designation', 'College', 'Course', 'Semester', 'Programme'])
            )->implode("\n");

        $response = $this->withoutExceptionHandling()
            ->get(route('teaching-records.export'))
            ->assertSuccessful();

        $this->assertInstanceOf(BinaryFileResponse::class, $response->baseResponse);
        $this->assertEquals(Storage::path('temp/exports'), $response->baseResponse->getFile()->getPath());

        ob_start();
        $response->sendContent();
        $this->assertEquals($expectedCSV, ob_get_contents());
        ob_end_clean();

        Storage::assertMissing('temp/exports/' . $response->getFile()->getBasename());
    }
}
