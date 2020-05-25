<?php

namespace Tests\Unit;

use App\Models\Scholar;
use App\Models\ScholarExaminer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScholarExaminerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function scholar_examiner_applied_on_is_the_created_at_attribute()
    {
        $scholarExaminer = create(ScholarExaminer::class);

        $this->assertEquals($scholarExaminer->created_at, $scholarExaminer->applied_on);
    }

    /** @test */
    public function scholar_examiner_belongs_to_a_scholar()
    {
        $scholar = create(Scholar::class);
        $scholarExaminer = create(ScholarExaminer::class, 1, [
            'scholar_id' => $scholar->id,
        ]);

        $this->assertInstanceOf(BelongsTo::class, $scholarExaminer->scholar());
        $this->assertTrue($scholar->is($scholarExaminer->scholar));
    }
}
