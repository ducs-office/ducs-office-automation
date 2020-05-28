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

    /** @test */
    public function co_author_belongs_to_a_user()
    {
        $user = create(User::class);
        $coAuthor = create(CoAuthor::class, 1, [
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(BelongsTo::class, $coAuthor->user());
        $this->assertTrue($user->is($coAuthor->user));
    }

    /** @test */
    public function it_gives_name()
    {
        $user = create(User::class);

        $coAuthor = new CoAuthor();
        $coAuthor->user_id = $user->id;

        $this->assertEquals($user->name, $coAuthor->name);

        $coAuthor->user_id = null;
        $coAuthor->name = 'John Due';

        $this->assertEquals('John Due', $coAuthor->name);
    }
}
