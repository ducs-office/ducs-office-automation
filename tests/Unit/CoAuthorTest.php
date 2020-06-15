<?php

namespace Tests\Unit;

use App\Models\CoAuthor;
use App\Models\Publication;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoAuthorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function co_author_belongs_to_a_publication()
    {
        $publication = create(Publication::class);
        $coAuthor = create(CoAuthor::class, 1, [
            'publication_id' => $publication->id,
        ]);

        $this->assertInstanceOf(BelongsTo::class, $coAuthor->publication());
        $this->assertTrue($publication->is($coAuthor->publication));
    }
}
