<?php

namespace Tests\Feature;

use App\Programme;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteProgrammeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_delete_any_programme()
    {
        $programme = create(Programme::class);
        $this->signIn();

        $this->delete('/programmes/'.$programme->id)
            ->assertRedirect('programmes')
            ->assertSessionHasFlash('success', 'Programme deleted successfully!');

        $this->assertNull($programme->fresh(), 'Programme still exists in database.');
    }
}
