<?php

namespace Tests\Unit;

use App\AcademicDetail;
use Tests\TestCase;

class AcademicDetailTest extends TestCase
{
    /** @test */
    public function array_of_authors_is_stored_separated_by_pipes()
    {
        $detail = new AcademicDetail();
        $authors = ['Pagak G.', 'Auilers K.'];

        $detail->authors = $authors;

        $this->assertEquals(implode('|', $authors), $detail->getAttributes()['authors']);
    }

    /** @test */
    public function authors_separated_by_pipe_returned_as_array()
    {
        $detail = new AcademicDetail();
        $authors = ['Pagak G.', 'Auilers K.'];

        $detail->authors = implode('|', $authors);

        $this->assertSame($authors, $detail->authors);
    }

    /** @test */
    public function array_of_indexed_in_is_stored_separated_by_pipe()
    {
        $detail = new AcademicDetail();
        $indexed_in = ['SCI', 'SCIE'];

        $detail->indexed_in = $indexed_in;

        $this->assertEquals(implode('|', $indexed_in), $detail->getAttributes()['indexed_in']);
    }

    /** @test */
    public function indexed_in_separated_by_pipe_returned_as_array()
    {
        $detail = new AcademicDetail();
        $indexed_in = ['SCI', 'SCIE'];
        $detail->indexed_in = implode('|', $indexed_in);

        $this->assertSame($indexed_in, $detail->indexed_in);
    }

    /** @test */
    public function array_of_page_numbers_is_stored_seperated_by_hyphen()
    {
        $detail = new AcademicDetail();
        $page_numbers = ['23', '49'];

        $detail->page_numbers = $page_numbers;

        $this->assertEquals(implode('-', $page_numbers), $detail->getAttributes()['page_numbers']);
    }

    public function page_numbers_separated_by_hyphen_returned_as_array()
    {
        $detail = new AcademicDetail();
        $page_numbers = ['23', '49'];

        $detail->page_numbers = implode('-', $page_numbers);

        $this->assertSame($page_numbers, $detail->page_numbers);
    }
}
