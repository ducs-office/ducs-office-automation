<?php

namespace Tests\Feature;

use App\Models\ProgressReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DeleteScholarProgressReportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function progress_report_can_be_deleted_if_they_are_authorized()
    {
        $progressRreport = create(ProgressReport::class);

        $this->assertEquals(1, ProgressReport::count());

        $role = Role::create(['name' => 'randomRole']);
        $role->givePermissionTo('scholar progress reports:delete');

        $this->signIn(create(User::class), $role->name);

        $this->withoutExceptionHandling()
            ->delete(route('scholars.progress_reports.destroy', [
                'scholar' => $progressRreport->scholar,
                'report' => $progressRreport,
            ]))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Progress Report deleted successfully');

        Storage::assertMissing($progressRreport->path);

        $this->assertEquals(0, ProgressReport::count());
    }

    /** @test */
    public function progress_report_can_not_be_deleted_if_they_are_not_authorized()
    {
        $progressRreport = create(ProgressReport::class);

        $this->assertEquals(1, ProgressReport::count());

        $role = Role::create(['name' => 'randomRole']);
        $role->revokePermissionTo('scholar progress reports:delete');

        $this->signIn(create(User::class), $role->name);

        $this->withExceptionHandling()
            ->delete(route('scholars.progress_reports.destroy', [
                'scholar' => $progressRreport->scholar,
                'report' => $progressRreport,
            ]));

        Storage::assertExists($progressRreport->path);

        $this->assertEquals(1, ProgressReport::count());
    }
}
