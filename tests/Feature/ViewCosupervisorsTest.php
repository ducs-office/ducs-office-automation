<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewCosupervisorsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function cosupervisors_can_be_viewed()
    {
        $this->signIn();

        $this->withoutExceptionHandling()
            ->get(route('staff.cosupervisors.index'))
            ->assertViewIs('staff.cosupervisors.index')
            ->assertViewHasAll(['cosupervisors', 'teachers', 'faculties']);
    }
}
