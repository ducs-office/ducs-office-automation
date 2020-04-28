<?php

namespace Tests\Feature;

use App\Models\Scholar;
use App\Models\ScholarDocument;
use App\Models\User;
use App\Types\ScholarDocumentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ScholarProgressReportTest extends TestCase
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
        $this->assertCount(0, $scholar->progressReports());

        $this->withoutExceptionHandling()
            ->post(route('research.scholars.progress_reports.store', $scholar), [
                'progress_report' => $progressReport,
                'description' => $description = 'Progres Report May-2019',
            ])
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Progress Report added successfully!');

        $this->assertCount(1, $scholar->fresh()->progressReports());

        $expectedPath = 'scholar_documents/' . $progressReport->hashName();

        $this->assertEquals($expectedPath, $scholar->fresh()->progressReports()->first()->path);
        $this->assertEquals($description, $scholar->fresh()->progressReports()->first()->description);

        Storage::assertExists($scholar->fresh()->progressReports()->first()->path);
    }

    /** @test */
    public function user_can_not_upload_progress_reports_when_they_don_not_have_permission_to_add()
    {
        $this->signIn($user = create(User::class));

        $user->revokePermissionTo('scholar progress reports:add');

        storage::fake();
        $progressReport = UploadedFile::fake()->create('fake_progress_report.pdf', 50, 'application/pdf');

        $scholar = create(Scholar::class);

        $this->assertCount(0, $scholar->progressReports());

        $this->withExceptionHandling()
            ->post(route('research.scholars.progress_reports.store', $scholar), [
                'progress_report' => $progressReport,
                'description' => $description = 'Progress Report Description',
            ])
            ->assertForbidden();

        $this->assertCount(0, $scholar->fresh()->progressReports());
    }

    /** @test */
    public function progess_report_can_be_viewed_if_they_are_authorized()
    {
        $scholar = create(Scholar::class);
        $progressReport = create(ScholarDocument::class, 1, [
            'scholar_id' => $scholar->id,
            'type' => ScholarDocumentType::PROGRESS_REPORT,
        ]);
        $role = Role::create(['name' => 'randomRole']);

        $role->givePermissionTo('scholars:view');

        $this->signIn($user = create(User::class), $role->name);

        $this->withoutExceptionHandling()
            ->get(route('research.scholars.progress_reports.attachment', [
                'scholar' => $scholar,
                'document' => $progressReport,
            ]))
            ->assertSuccessful();
    }

    /** @test */
    public function progess_report_can_not_be_viewed_if_they_are_not_authorized()
    {
        $role = Role::create(['name' => 'randomRole']);
        $role->revokePermissionTo('scholars:view');

        $this->signIn($user = create(User::class), $role->name);

        $user->revokePermissionTo('scholars:view');

        $scholar = create(Scholar::class);
        $progressReport = create(ScholarDocument::class, 1, [
            'scholar_id' => $scholar->id,
            'type' => ScholarDocumentType::PROGRESS_REPORT,
        ]);

        $this->withExceptionHandling()
            ->get(route('research.scholars.progress_reports.attachment', [
                'scholar' => $scholar,
                'document' => $progressReport,
            ]))
            ->assertForbidden();
    }
}
