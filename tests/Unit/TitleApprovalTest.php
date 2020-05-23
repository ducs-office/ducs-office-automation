<?php

namespace Tests\Unit;

use App\Models\Scholar;
use App\Models\TitleApproval;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TitleApprovalTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function applied_on_is_the_created_at_attribute()
    {
        $titleApproval = create(TitleApproval::class);

        $this->assertEquals($titleApproval->created_at->format('d F Y'), $titleApproval->applied_on);
    }

    /** @test */
    public function pre_phd_seminar_belongs_to_a_scholar()
    {
        $scholar = create(Scholar::class);
        $titleApproval = create(TitleApproval::class, 1, [
            'scholar_id' => $scholar,
        ]);

        $this->assertInstanceOf(BelongsTo::class, $titleApproval->scholar());
        $this->assertTrue($scholar->is($titleApproval->scholar));
    }
}
