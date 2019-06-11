<?php


namespace Tests\Unit;


use App\map\Map;
use PHPUnit\Framework\TestCase;

class MapTest extends TestCase
{
    protected $map;

    protected function setUp(): void
    {
        parent::setUp();
        $this->map = new Map();
    }

    /** @test */
    function newly_created_map_should_be_empty()
    {
        $this->assertEquals(0, $this->map->size());
    }

    /** @test */
    function after_value_put_size_should_be_1()
    {
        $this->map->put('key', 'value');
        $this->assertEquals(1, $this->map->size());
    }

    /** @test */
    function when_one_is_removed_size_should_decrement()
    {
        $this->map->put('key', 'value');
        $this->map->remove('key');
        $this->assertEquals(0, $this->map->size());
    }

    /** @test */
    function when_key_put_with_value_should_get_value()
    {
        $this->map->put('key', 'value');
        $this->assertEquals('value', $this->map->get('key'));
    }

    /** @test */
    function when_new_value_is_put_with_previous_key_should_overwrite()
    {
        $this->map->put('key', 'value');
        $this->map->put('key', 'value2');
        $this->assertEquals('value2', $this->map->get('key'));
    }

    /** @test */
    function when_key_does_not_exist_get_should_return_null()
    {
        $this->assertNull($this->map->get('key'));
    }

    /** @test */
    function when_removed_key_get_should_return_null()
    {
        $this->map->put('key', 'value');
        $this->map->remove('key');
        $this->assertNull($this->map->get('key'));
    }

    /** @test */
    function when_given_numeric_keys_should_work()
    {
        $this->map->put(1, 'value');
        $this->map->remove(1);
        $this->map->put(1, 'value2');
        $this->assertEquals('value2', $this->map->get(1));
    }

    /** @test */
    function when_clear_size_should_be_zero()
    {
        $this->map->put(1, 'value');
        $this->map->put(2, 'value');
        $this->map->clear();
        $this->assertEquals(0, $this->map->size());
        $this->assertNull($this->map->get(1));
    }


}