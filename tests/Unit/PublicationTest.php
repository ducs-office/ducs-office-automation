<?php

namespace Tests\Unit;

use App\Presentation;
use App\Publication;
use App\Scholar;
use App\SupervisorProfile;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
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

    /** @test */
    public function publication_belongs_to_main_author_via_morphTo()
    {
        $scholar = create(Scholar::class);

        $publication = create(Publication::class, 1, [
            'main_author_type' => Scholar::class,
            'main_author_id' => $scholar->id,
        ]);

        $this->assertInstanceOf(MorphTo::class, $publication->mainAuthor());
        $this->assertTrue($publication->mainAuthor->is($scholar));

        $supervisorProfile = create(SupervisorProfile::class);

        $publication = create(Publication::class, 1, [
            'main_author_type' => SupervisorProfile::class,
            'main_author_id' => $supervisorProfile->id,
        ]);

        $this->assertInstanceOf(MorphTo::class, $publication->mainAuthor());
        $this->assertTrue($publication->mainAuthor->is($supervisorProfile));
    }
}
