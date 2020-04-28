<?php

namespace Tests\Unit;

use App\Models\Scholar;
use App\Models\ScholarDocument;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScholarDocumentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function scholar_document_belongs_to_a_scholar()
    {
        $scholar = create(Scholar::class);

        $scholarDocument = create(ScholarDocument::class, 1, [
            'scholar_id' => $scholar->id,
        ]);

        $this->assertInstanceOf(BelongsTo::class, $scholarDocument->scholar());
        $this->assertTrue($scholar->is($scholarDocument->fresh()->scholar));
    }
}
