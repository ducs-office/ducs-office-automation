<?php

namespace Tests\Unit;

use App\Models\PrePhdSeminar;
use App\Models\Scholar;
use App\Types\RequestStatus;
use App\Types\ScholarAppealTypes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PrePhdSeminarTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pre_ph_seminar_applied_on_is_the_created_at_attribute()
    {
        $prePhdSeminar = create(PrePhdSeminar::class);

        $this->assertEquals($prePhdSeminar->created_at, $prePhdSeminar->applied_on);
    }

    /** @test */
    public function pre_phd_seminar_belongs_to_a_scholar()
    {
        $scholar = create(Scholar::class);
        $prePhdSeminar = create(PrePhdSeminar::class, 1, [
            'scholar_id' => $scholar,
        ]);

        $this->assertInstanceOf(BelongsTo::class, $prePhdSeminar->scholar());
        $this->assertTrue($scholar->is($prePhdSeminar->scholar));
    }

    /** @test */
    public function isCompleted_method_returns_true_when_status_pre_phd_seminar_is_approved()
    {
        $scholar = create(Scholar::class);

        $this->assertNull($scholar->prePhdSeminar);

        $appeal = create(PrePhdSeminar::class, 1, [
            'scholar_id' => $scholar->id,
            'status' => RequestStatus::APPLIED,
        ]);

        $this->assertFalse($appeal->isCompleted());

        $appeal->update(['status' => RequestStatus::RECOMMENDED]);

        $this->assertFalse($appeal->isCompleted());

        $appeal->update(['status' => RequestStatus::APPROVED]);

        $this->assertTrue($appeal->isCompleted());
    }
}
