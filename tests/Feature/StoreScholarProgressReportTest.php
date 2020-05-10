<?php

namespace Tests\Feature;

use App\Models\ProgressReport;
use App\Models\Scholar;
use App\Models\ScholarDocument;
use App\Models\User;
use App\Types\ProgressReportRecommendation;
use App\Types\ScholarDocumentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class StoreScholarProgressReportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_upload_progress_reports_when_they_have_permission_to_add()
    {
        $this->signIn($user = create(User::class));

        $user->givePermissionTo('scholar progress reports:add');

        Storage::fake();
        $progressReport = UploadedFile::fake()->create('fake_progress_report.pdf', 50, 'application/pdf');

        $scholar = create(Scholar::class);
        $this->assertCount(0, $scholar->progressReports);

        $this->withoutExceptionHandling()
            ->post(route('scholars.progress_reports.store', $scholar), [
                'progress_report' => $progressReport,
                'recommendation' => $recommendation = Arr::random(array_values(ProgressReportRecommendation::values())),
                'date' => $date = '2019-09-12',
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Progress Report added successfully!');

        $updatedScholar = $scholar->fresh();

        $this->assertCount(1, $updatedScholar->progressReports);

        $expectedPath = 'progress_reports/' . $progressReport->hashName();

        $this->assertEquals($expectedPath, $updatedScholar->progressReports->first()->path);
        $this->assertEquals($recommendation, $updatedScholar->progressReports->first()->recommendation);
        $this->assertEquals($date, $updatedScholar->progressReports->first()->date->format('Y-m-d'));

        Storage::assertExists($updatedScholar->progressReports->first()->path);
    }

    /** @test */
    public function user_can_not_upload_progress_reports_when_they_don_not_have_permission_to_add()
    {
        $this->signIn($user = create(User::class));

        $user->revokePermissionTo('scholar progress reports:add');

        storage::fake();
        $progressReport = UploadedFile::fake()->create('fake_progress_report.pdf', 50, 'application/pdf');

        $scholar = create(Scholar::class);

        $this->assertCount(0, $scholar->progressReports);

        $this->withExceptionHandling()
            ->post(route('scholars.progress_reports.store', $scholar), [
                'progress_report' => $progressReport,
                'recommendation' => $recommendation = Arr::random(array_values(ProgressReportRecommendation::values())),
                'date' => $date = '2019-09-12',
            ])
            ->assertForbidden();

        $this->assertCount(0, $scholar->fresh()->progressReports);
    }

    /** @test */
    public function request_validates_description_is_valid_description()
    {
        $this->signIn($user = create(User::class));

        $user->givePermissionTo('scholar progress reports:add');

        storage::fake();
        $progressReport = UploadedFile::fake()->create('fake_progress_report.pdf', 50, 'application/pdf');

        $scholar = create(Scholar::class);

        $this->assertCount(0, $scholar->progressReports);

        try {
            $this->withoutExceptionHandling()
                ->post(route('scholars.progress_reports.store', $scholar), [
                    'progress_report' => $progressReport,
                    'recommendation' => $recommendation = 'Progress Report Description',
                    'date' => $date = '2019-09-12',
                ]);
        } catch (ValidationException $e) {
            $this->assertArrayHasKey('recommendation', $e->errors());
        }

        $this->assertCount(0, $scholar->fresh()->progressReports);

        $this->withoutExceptionHandling()
            ->post(route('scholars.progress_reports.store', $scholar), [
                'progress_report' => $progressReport,
                'recommendation' => $recommendation = ProgressReportRecommendation::CONTINUE,
                'date' => $date = '2019-09-12',
            ]);

        $this->assertCount(1, $scholar->fresh()->progressReports);
    }
}
