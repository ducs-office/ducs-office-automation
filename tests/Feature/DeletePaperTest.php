<?php

namespace Tests\Feature;

use App\Paper;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeletePaperTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_delete_paper()
    {
        $this->signIn();

        $paper = create(Paper::class);

        $this->withoutExceptionHandling()
            ->delete('/papers/'.$paper->id)
            ->assertRedirect('/papers')
            ->assertSessionHasFlash('success', 'Paper deleted successfully!');

        $this->assertNull($paper->fresh(), 'Paper was not deleted!');
    }
}
