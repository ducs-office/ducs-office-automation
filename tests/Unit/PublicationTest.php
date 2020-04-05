<?php

namespace Tests\Unit;

use App\Presentation;
use App\Publication;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function publication_has_many_presentations()
    {
        $publication = create(Publication::class);
        $this->assertInstanceOf(HasMany::class, $publication->presentations());
        $this->assertCount(0, $publication->presentations);

        $presentation = create(Presentation::class, 1, [
            'publication_id' => $publication->id,
        ]);

        $this->assertCount(1, $publication->fresh()->presentations);
        $this->assertTrue($presentation->is($publication->presentations()->first()));
    }
}
