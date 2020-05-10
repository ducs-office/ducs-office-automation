<?php

namespace Tests\Feature;

use App\Models\ProgressReport;
use App\Models\Scholar;
use App\Models\ScholarDocument;
use App\Models\User;
use App\Types\ScholarDocumentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ViewScholarProgressReportTest extends TestCase
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
            ->get(route('scholars.progress_reports.show', [
                'scholar' => $scholar,
                'report' => $progressReport,
            ]))
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
            ->get(route('scholars.progress_reports.show', [
                'scholar' => $scholar,
                'report' => $progressReport,
            ]))
            ->assertForbidden();
    }

    /** @test */
    public function progess_report_can_be_viewed_if_they_are_authorized()
    {
        $scholar = create(Scholar::class);
        $progressReport = create(ProgressReport::class, 1, [
            'scholar_id' => $scholar->id,
        ]);
        $role = Role::create(['name' => 'randomRole']);

        $role->givePermissionTo('scholar progress reports:view');

        $this->signIn($user = create(User::class), $role->name);

        $this->withoutExceptionHandling()
            ->get(route('scholars.progress_reports.show', [
                'scholar' => $scholar,
                'report' => $progressReport,
            ]))
            ->assertSuccessful();
    }

    /** @test */
    public function progess_report_can_not_be_viewed_if_they_are_not_authorized()
    {
        $role = Role::create(['name' => 'randomRole']);
        $role->revokePermissionTo('scholars:view');

        $this->signIn($user = create(User::class), $role->name);

        $user->revokePermissionTo('scholar progress reports:view');

        $scholar = create(Scholar::class);
        $progressReport = create(ProgressReport::class, 1, [
            'scholar_id' => $scholar->id,
        ]);

        $this->withExceptionHandling()
            ->get(route('scholars.progress_reports.show', [
                'scholar' => $scholar,
                'report' => $progressReport,
            ]))
            ->assertForbidden();
    }
}
