<?php

namespace Tests\Unit;

use App\Models\ScholarAppeal;
use App\Types\ScholarAppealTypes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ScholarAppealTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function scholar_appeal_class_has_a_phdSeminarAppeals_scope_that_returns_by_created_at_desc()
    {
        $phdSeminarAppeal1 = create(ScholarAppeal::class, 1, [
            'type' => ScholarAppealTypes::PRE_PHD_SEMINAR,
        ]);

        Carbon::setTestNow($appealTime = now()->addDay());

        $phdSeminarAppeal2 = create(ScholarAppeal::class, 1, [
            'type' => ScholarAppealTypes::PRE_PHD_SEMINAR,
        ]);

        $this->assertCount(2, ScholarAppeal::phdSeminarAppeals());
        $this->assertEquals(
            [
                $phdSeminarAppeal2->id,
                $phdSeminarAppeal1->id,
            ],
            ScholarAppeal::phdSeminarAppeals()->pluck('id')->toArray()
        );
    }

    /** @test */
    public function applied_on_is_the_created_at_attribute()
    {
        $appeal = create(ScholarAppeal::class);

        $this->assertEquals($appeal->created_at->format('d F Y'), $appeal->applied_on);
    }
}
