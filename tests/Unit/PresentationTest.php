<?php

namespace Tests\Unit;

use App\Models\Presentation;
use App\Models\Publication;
use App\Models\Scholar;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PresentationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function presentation_belongs_to_a_publication()
    {
        $publication = create(Publication::class);
        $presentation = create(Presentation::class, 1, [
            'publication_id' => $publication->id,
        ]);

        $this->assertInstanceOf(BelongsTo::class, $presentation->publication());
        $this->assertTrue($publication->is($presentation->publication));
    }

    /** @test */
    public function presentation_belongs_to_a_scholar()
    {
        $scholar = create(Scholar::class);
        $presentation = create(Presentation::class, 1, [
            'scholar_id' => $scholar->id,
        ]);

        $this->assertInstanceOf(BelongsTo::class, $presentation->scholar());
        $this->assertTrue($scholar->is($presentation->scholar));
    }
}
