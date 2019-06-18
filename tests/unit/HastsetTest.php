<?php

namespace Tests\Unit;

use App\Hashset\Hashset;
use PHPUnit\Framework\TestCase;

class HastsetTest extends TestCase
{
    /** @var Hashset */
    private $hashset;

    protected function setUp(): void
    {
        parent::setUp();
        $this->hashset = new Hashset();
    }

    /** @test */
    function newly_created_hashset_should_have_size_0()
    {
        $this->assertEquals(0, $this->hashset->size());
    }

    /** @test */
    function can_add_value_to_it()
    {
        $this->hashset->add(1);
        $this->hashset->add(2);
        $this->assertTrue($this->hashset->contains(1));
        $this->assertTrue($this->hashset->contains(2));
    }

    /** @test */
    function when_given_duplicate_values_should_retain_only_one()
    {
        $this->hashset->add(1);
        $this->hashset->add(1);
        $this->assertEquals(1, $this->hashset->size());
    }

    /** @test */
    function when_given_1_and_removed_should_not_contain_1()
    {
        $this->hashset->add(1);
        $this->hashset->add(2);
        $this->hashset->remove(1);
        $this->assertFalse($this->hashset->contains(1));
        $this->assertEquals(1, $this->hashset->size());
    }

    /** @test */
    function when_none_existent_element_removed_size_should_remain_constant()
    {
        $this->hashset->add(1);
        $this->hashset->remove(1);
        $this->hashset->remove(1);
        $this->assertEquals(0, $this->hashset->size());
    }

    /** @test */
    function when_elements_are_added_should_expand_dynamically()
    {
        $this->hashset->add(1);
        $this->hashset->add(1);
        $this->hashset->add(2);
        $this->hashset->add(3);
        $this->hashset->add(4);
        $this->hashset->add(5);
        $this->assertEquals(5, $this->hashset->size());
        $this->assertEquals(5,$this->hashset->contains(5));
        $this->assertEquals(1,$this->hashset->contains(1));

    }

}