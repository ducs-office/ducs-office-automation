<?php

namespace Tests\Feature;

use App\Models\Scholar;
use App\Models\ScholarDocument;
use App\Types\ScholarDocumentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ScholarViewOtherDocumentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function scholar_can_view_their_other_documents()
    {
        $this->signInScholar($scholar = create(Scholar::class));

        $otherDocument = create(ScholarDocument::class, 1, [
            'type' => ScholarDocumentType::OTHER_DOCUMENT,
            'scholar_id' => $scholar->id,
        ]);

        $this->withoutExceptionHandling()
            ->get(route('scholars.documents.attachment', $otherDocument))
            ->assertSuccessful();
    }

    /** @test */
    public function scholar_can_not_view_others_other_documents()
    {
        $this->signInScholar();

        $scholar = create(Scholar::class);

        $otherDocument = create(ScholarDocument::class, 1, [
            'type' => ScholarDocumentType::OTHER_DOCUMENT,
            'scholar_id' => $scholar->id,
        ]);

        $this->withExceptionHandling()
            ->get(route('scholars.documents.attachment', $otherDocument))
            ->assertForbidden();
    }
}
