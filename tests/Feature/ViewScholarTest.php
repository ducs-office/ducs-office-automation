<?php

namespace Tests\Feature;

use App\Scholar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewScholarTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function scholars_can_be_viewed()
    {
        $this->signIn();

        create(Scholar::class, 3);

        $scholars = $this->withoutExceptionHandling()
            ->get(route('staff.scholars.index'))
            ->assertViewHas('scholars')
            ->viewData('scholars');

        $this->assertCount(3, $scholars);
    }
}
