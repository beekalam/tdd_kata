<?php

namespace Tests\Unit;

use App\collections\CollectionException;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{

    /** @test */
    function can_return_all_items_in_input_array()
    {
        $res = collect([1, 2, 3])->all();
        $this->assertEquals([1, 2, 3], $res);
    }

    /** @test */
    function can_calculate_average_of_array()
    {
        $average = collect([['foo' => 10], ['foo' => 10], ['foo' => 20], ['foo' => 40]])->avg('foo');
        $this->assertEquals(20, $average);

        $average = collect([1, 1, 2, 4])->avg();
        $this->assertEquals(2, $average);
    }

    /** @test */
    function when_calculating_average_of_array_of_array_key_should_exist()
    {
        $this->expectException(CollectionException::class);
        collect([[10], ['foo' => 10], ['foo' => 20], ['foo' => 40]])->avg('foo');
    }

    /** @test */
    function can_chunk_input_array()
    {
        $collection = collect([1, 2, 3, 4, 5, 6, 7]);

        $chunks = $collection->chunk(4);

        $this->assertEquals([[1, 2, 3, 4], [5, 6, 7]], $chunks->toArray());
    }

    /** @test */
    function can_collapse_array_of_arrays_to_a_flat_array()
    {
        $collection = collect([[1, 2, 3], [4, 5, 6], [7, 8, 9]]);

        $collapsed = $collection->collapse();

        $collapsed->all();

        $this->assertEquals([1, 2, 3, 4, 5, 6, 7, 8, 9], $collapsed->all());
    }

    /** @test */
    function can_combine_collections_array_with_another_array()
    {
        $collection = collect(['name', 'age']);

        $combined = $collection->combine(['George', 29]);

        $this->assertEquals(['name' => 'George', 'age' => 29], $combined->all());
    }

    /** @test */
    function can_append_given_array_to_the_end_of_collection()
    {
        $collection = collect(['John Doe']);

        $concatenated = $collection->concat(['Jane Doe'])->concat(['name' => 'Johnny Doe']);

        $concatenated->all();

        $this->assertEquals(['John Doe', 'Jane Doe', 'Johnny Doe'], $concatenated->all());
    }

    /** @test */
    function can_append_collection_to_collection()
    {
        $collection = collect(['John Doe']);

        $concatenated = $collection->concat(collect(['Jane Doe']))->concat(collect(['name' => 'Johnny Doe']));

        $concatenated->all();

        $this->assertEquals(['John Doe', 'Jane Doe', 'Johnny Doe'], $concatenated->all());
    }

    /** @test */
    function can_determine_if_collection_contains_a_given_item()
    {
        $collection = collect(['name' => 'Desk', 'price' => 100]);
        $collection->contains('Desk');

        $this->assertTrue($collection->contains('Desk'));
        $this->assertFalse($collection->contains('New York'));
    }

    /** @test */
    function contains_may_also_accept_key_value_pairs()
    {
        $collection = collect([
            ['product' => 'Desk', 'price' => 200],
            ['product' => 'Chair', 'price' => 100],
        ]);

        $this->assertFalse($collection->contains('product', 'Bookcase'));
    }

    /** @test */
    function contains_may_accept_a_callback()
    {
        $collection = collect([1, 2, 3, 4, 5]);

        $this->assertFalse($collection->contains(function ($value, $key) {
            return $value > 5;
        }));
    }

    /** @test */
    function can_return_total_number_of_items_in_collection()
    {
        $collection = collect([1, 2, 3, 4]);

        $this->assertEquals(4, $collection->count());
    }

    /** @test */
    function can_count_the_occurrence_of_values_in_collection()
    {
        $collection = collect([1, 2, 2, 2, 3]);

        $counted = $collection->countBy();

        $this->assertEquals([1 => 1, 2 => 3, 3 => 1], $counted->all());
    }

    /** @test */
    function countBy_can_accept_a_callback_that_can_count_by_custom_value()
    {
        $collection = collect(['alice@gmail.com', 'bob@yahoo.com', 'carlos@gmail.com']);

        $counted = $collection->countBy(function ($email) {
            return substr(strrchr($email, "@"), 1);
        });

        $this->assertEquals(['gmail.com' => 2, 'yahoo.com' => 1], $counted->all());
    }

    /** @test */
    function can_return_cartesian_product_of_collection_values_with_an_array()
    {
        $collection = collect([1, 2]);

        $matrix = $collection->crossJoin(['a', 'b']);

        $expected = [
            [1, 'a'],
            [1, 'b'],
            [2, 'a'],
            [2, 'b'],
        ];
        $this->assertEquals($expected, $matrix->all());
    }

    /** @test */
    function can_return_crossjoin_of_a_variable_number_of_array()
    {
        $collection = collect([1, 2]);

        $matrix = $collection->crossJoin(['a', 'b'], ['I', 'II']);

        $matrix->all();

        $expectd =
            [
                [1, 'a', 'I'],
                [1, 'a', 'II'],
                [1, 'b', 'I'],
                [1, 'b', 'II'],
                [2, 'a', 'I'],
                [2, 'a', 'II'],
                [2, 'b', 'I'],
                [2, 'b', 'II'],
            ];
        $this->assertEquals($expectd, $matrix->all());
    }

    /** @test */
    function can_return_values_in_collection_that_are_not_in_another_array()
    {
        $collection = collect([1, 2, 3, 4, 5]);

        $diff = $collection->diff([2, 4, 6, 8]);

        $this->assertEquals([1, 3, 5], $diff->all());
    }

    /** @test */
    function can_return_values_in_collection_that_are_not_in_another_associative_array()
    {
        $collection = collect([
            'color'  => 'orange',
            'type'   => 'fruit',
            'remain' => 6
        ]);

        $diff = $collection->diffAssoc([
            'color'  => 'yellow',
            'type'   => 'fruit',
            'remain' => 3,
            'used'   => 6
        ]);

        $this->assertEquals(['color' => 'orange', 'remain' => 6], $diff->all());
    }

    /** @test */
    function can_return_values_in_collection_that_are_in_another_array_based_on_their_keys()
    {
        $collection = collect([
            'one'   => 10,
            'two'   => 20,
            'three' => 30,
            'four'  => 40,
            'five'  => 50,
        ]);

        $diff = $collection->diffKeys([
            'two'   => 2,
            'four'  => 4,
            'six'   => 6,
            'eight' => 8,
        ]);

        $diff->all();

        $this->assertEquals(['one' => 10, 'three' => 30, 'five' => 50], $diff->all());
    }

    /** @test */
    function can_retrieve_and_return_duplicate_values_from_collection()
    {
        $collection = collect(['a', 'b', 'a', 'c', 'b']);

        $this->assertEquals(['a', 'b'], $collection->duplicates());
    }

    /** @test */
    function can_retrieve_and_return_duplicate_values_from_collection_on_associative_array()
    {
        $employees = collect([
            ['email' => 'abigail@example.com', 'position' => 'Developer'],
            ['email' => 'james@example.com', 'position' => 'Designer'],
            ['email' => 'victoria@example.com', 'position' => 'Developer'],
        ]);

        $this->assertEquals(['Developer'], $employees->duplicates('position'));
    }

    /** @test */
    function can_iterate_through_items_using_each_method()
    {
        $collection = collect([1, 2, 3, 4]);
        $collection->each(function ($item, $key) {
            return $item * 2;
        });

        $this->assertEquals([2, 4, 6, 8], $collection->all());
    }

    /** @test */
    function each_should_stop_the_loop_when_false_returned()
    {
        $collection = collect([1, 2, 3, 4]);
        $collection->each(function ($item, $key) {
            if ($item > 2) return false;
            return $item * 2;
        });

        $this->assertEquals([2, 4, 3, 4], $collection->all());
    }

    /** @test */
    function eachSpread_can_iterate_over_collection_items_and_pass_nested_item_to_callback()
    {
        $collection = collect([['John Doe', 35], ['Jane Doe', 33]]);

        $collection->eachSpread(function ($name, $age) {
            return [$name, $age+1];
        });

        $this->assertEquals([['John Doe', 36], ['Jane Doe', 34]], $collection->all());
    }

    /** @test */
    function can_return_from_eachSpread_by_returning_false()
    {
        $collection = collect([['John Doe', 35], ['Jane Doe', 33]]);

        $collection->eachSpread(function ($name, $age) {
            if($age == 33) return false;
            return [$name, $age+1];
        });

        $this->assertEquals([['John Doe', 36], ['Jane Doe', 33]], $collection->all());
    }


}