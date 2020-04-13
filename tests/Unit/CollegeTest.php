<?php

namespace Tests\Unit;

use App\Models\College;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\TestCase;

class CollegeTest extends TestCase
{
    /** @test */
    public function principal_phones_attribute_is_casted_to_array()
    {
        $college = new College();

        $this->assertArrayHasKey('principal_phones', $college->getCasts());
        $this->assertEquals('array', $college->getCasts()['principal_phones']);
    }

    /** @test */
    public function principal_emails_attribute_is_casted_to_array()
    {
        $college = new College();

        $this->assertArrayHasKey('principal_emails', $college->getCasts());
        $this->assertEquals('array', $college->getCasts()['principal_emails']);
    }
}
