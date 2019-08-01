<?php

namespace Tests\Unit;

use App\pyslicer\PySlicer;
use PHPUnit\Framework\TestCase;
use function App\pyslicer\slice;

class PySlicerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    function can_slice_with_positive_start_end()
    {
        $s = new PySlicer([1, 2, 3, 4, 5]);
        $this->assertEquals([2, 3], $s->slicer(1, 3));
    }

    /** @test */
    function can_slice_with_one_element()
    {
        $s = new PySlicer([1, 2, 3, 4, 5]);
        $this->assertEquals([2], $s->slicer(1, 2));
    }

    /** @test */
    function can_slice_zero_elements()
    {
        $s = new PySlicer([1, 2, 3, 4, 5]);
        $this->assertEquals([], $s->slicer(1, 1));
    }

    /** @test */
    function can_slice_zero_elements_with_index_zero()
    {
        $s = new PySlicer([1, 2, 3, 4, 5]);
        $this->assertEquals([], $s->slicer(0, 0));
    }

    /** @test */
    function can_slice_with_step()
    {
        $s = new PySlicer([1, 2, 3, 4, 5]);
        $this->assertEquals([1, 3], $s->slicer(0, 4, 2));
    }

    /** @test */
    function can_accept_string_paramter()
    {
        $s = new PySlicer([1, 2, 3, 4, 5]);
        $this->assertEquals([2], $s->slicer('1:2'));
    }

    /** @test */
    function can_use_helper_function_to_slice()
    {
        $this->assertEquals([2], PySlicer::slice("1:2", [1, 2, 3, 4, 5]));
    }

    /** @test */
    function can_slice_with_no_end()
    {
        $l = ['spam', 'Spam', 'SPAM!'];
        $s = new PySlicer($l);
        $this->assertEquals(['Spam', 'SPAM!'], $s->slicer(1));
    }

    /** @test */
    function can_slice_with_no_end_with_input_string()
    {
        $l = ['spam', 'Spam', 'SPAM!'];
        $s = new PySlicer($l);
        $this->assertEquals(['Spam', 'SPAM!'], PySlicer::slice("1:", $l));
    }

}

