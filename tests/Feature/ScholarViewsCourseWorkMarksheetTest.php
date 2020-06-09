<?php

namespace Tests\Feature;

use App\Models\PhdCourse;
use App\Models\Scholar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ScholarViewsCourseWorkMarksheetTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function scholar_can_view_marksheet_of_their_completed_courses()
    {
        Storage::fake();

        $this->signInScholar($scholar = create(Scholar::class));

        $course = create(PhdCourse::class);

        $marksheetPath = UploadedFile::fake()->create('fakefile.pdf', 20, 'application/pdf')->store('scholar_marksheets');

        $scholar->courseworks()->attach($course->id, [
            'marksheet_path' => $marksheetPath,
            'completed_on' => now()->format('Y-m-d'),
        ]);

        $this->withoutExceptionHandling()
            ->get(route('scholars.courseworks.marksheet', [$scholar, $course]))
            ->assertSuccessful();
    }

    /** @test */
    public function scholar_can_not_view_marksheet_of_other_scholars_completed_courses()
    {
        Storage::fake();

        $this->signInScholar($scholar = create(Scholar::class));

        $course = create(PhdCourse::class);

        $marksheetPath = UploadedFile::fake()->create('fakefile.pdf', 20, 'application/pdf')->store('scholar_marksheets');

        $otherScholar = create(Scholar::class);

        $otherScholar->courseworks()->attach($course->id, [
            'marksheet_path' => $marksheetPath,
            'completed_on' => now()->format('Y-m-d'),
        ]);

        $this->withExceptionHandling()
            ->get(route('scholars.courseworks.marksheet', [$otherScholar, $course]))
            ->assertForbidden();
    }
}
