<?php

namespace Tests\Unit;


use App\Set\Set;
use PHPUnit\Framework\TestCase;

class SetTest extends TestCase
{
    /**
     * @var Set
     */
    private $set;

    protected function setUp(): void
    {
        parent::setUp();
        $this->set = new Set();
    }

    /** @test */
    function when_add_1_should_have_size_1()
    {
        $this->set->add(1);
        $this->set->add(2);
        $this->assertEquals(2, $this->set->size());
    }

    /** @test */
    function when_an_array_values_added_should_contain_them()
    {
        $this->set->add([1, 2, 3, 1, 2, 3]);
        $this->assertEquals(3, $this->set->size());
    }

    /** @test */
    function when_add_1_should_get_true_on_contains()
    {
        $this->set->add(1);
        $this->assertTrue($this->set->contains(1));
    }

    /** @test */
    function when_add_1_and_remove_1_set_should_be_empty()
    {
        $this->set->add(1);
        $this->set->remove(1);
        $this->assertTrue($this->set->isEmpty());
        $this->assertFalse($this->set->contains(1));
    }

    /** @test */
    function when_add_1_2_3_should_return_array_of_values()
    {
        $this->set->add(1);
        $this->set->add(2);
        $this->set->add(3);
        $this->assertTrue(6 === array_sum($this->set->toArray()));
    }

    /** @test */
    function given_two_set_should_return_union()
    {
        $set1 = new Set([1, 2, 3]);
        $set2 = new Set([1, 2, 4]);
        $arr1 = [1,2,3,4];
        sort($arr1);
        $arr2 = Set::union($set1, $set2);
        $arr2 = $arr2->toArray();
        sort($arr2);
        $this->assertTrue($arr1 == $arr2);
    }

    /** @test */
    function given_two_sets_should_return_intersection()
    {
        $set1 = new Set([1, 2, 3]);
        $set2 = new Set([1, 2, 4]);
        $arr1 = array_intersect($set1->toArray(), $set2->toArray());
        sort($arr1);
        $arr2 = Set::intersect($set1, $set2);
        $arr2 = $arr2->toArray();
        sort($arr2);
        $this->assertTrue($arr1 == $arr2);
    }

}