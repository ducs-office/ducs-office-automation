<?php

namespace Tests\Feature;

use App\Scholar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteScholarTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function scholar_can_be_deleted()
    {
        $this->signIn();

        $scholar = create(Scholar::class);

        $this->withoutExceptionHandling()
            ->delete(route('staff.scholars.destroy', $scholar))
            ->assertRedirect()
            ->assertSessionHasFlash('success', 'Scholar deleted successfully!');

        $this->assertEquals(0, Scholar::count());
    }
}
