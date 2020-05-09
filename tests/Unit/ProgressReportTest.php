<?php

namespace Tests\Unit;

use App\Models\ProgressReport;
use App\Models\Scholar;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProgressReportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function progress_report_belongs_to_scholar()
    {
        $scholar = create(Scholar::class);

        $progressReport = create(ProgressReport::class, 1, [
            'scholar_id' => $scholar->id,
        ]);

        $this->assertInstanceOf(BelongsTo::class, $progressReport->scholar());
        $this->assertTrue($scholar->is($progressReport->scholar));
    }
}
