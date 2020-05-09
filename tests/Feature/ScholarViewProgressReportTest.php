<?php

namespace Tests\Feature;

use App\Models\ProgressReport;
use App\Models\Scholar;
use App\Models\ScholarDocument;
use App\Types\ScholarDocumentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ScholarViewProgressReportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function scholar_can_view_their_progress_report()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $progressReport = create(ProgressReport::class, 1, [
            'scholar_id' => $scholar->id,
        ]);

        $this->withoutExceptionHandling()
            ->get(route('scholars.progress_reports.attachment', $progressReport))
            ->assertSuccessful();
    }

    /** @test */
    public function scholar_can_not_view_others_progress_report()
    {
        $this->signInScholar();

        $scholar = create(Scholar::class);

        $progressReport = create(ProgressReport::class, 1, [
            'scholar_id' => $scholar->id,
        ]);

        $this->withExceptionHandling()
            ->get(route('scholars.progress_reports.attachment', $progressReport))
            ->assertForbidden();
    }
}
