<?php

namespace Tests\Unit;

use App\Models\College;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\TestCase;

class CollegeTest extends TestCase
{
    /** @test */
    public function colleges_principal_phones_stores_array_of_phone_numbers_seperated_by_pipe()
    {
        $college = new College();
        $phones = ['9876543210', '8765432109'];
        $college->principal_phones = $phones;

        $this->assertEquals(
            implode('|', $phones),
            $college->getAttributes()['principal_phones']
        );
    }

    /** @test */
    public function colleges_principal_phones_seperated_by_pipe_are_returned_as_array()
    {
        $college = new College();
        $phones = ['9876543210', '8765432109'];
        $college->principal_phones = implode('|', $phones);

        $this->assertSame($phones, $college->principal_phones);
    }

    /** @test */
    public function colleges_principal_emails_stores_array_of_emails_seperated_by_pipe()
    {
        $college = new College();
        $emails = ['john@example.com', 'jane@eaxmple.com'];
        $college->principal_emails = $emails;

        $this->assertEquals(
            implode('|', $emails),
            $college->getAttributes()['principal_emails']
        );
    }

    /** @test */
    public function colleges_principal_emails_seperated_by_pipe_are_returned_as_array()
    {
        $college = new College();
        $phones = ['9876543210', '8765432109'];
        $college->principal_phones = implode('|', $phones);

        $this->assertSame($phones, $college->principal_phones);
    }
}
