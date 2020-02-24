<?php

namespace Tests\Unit;

use App\Scholar;
use App\ScholarProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScholarTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function scholar_profile_is_created_on_creating_new_scholar()
    {
        $scholar = create(Scholar::class);

        $this->assertEquals(1, ScholarProfile::count());
        $this->assertEquals($scholar->id, ScholarProfile::first()->scholar_id);
    }
}
