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

        $this->assertEquals([1, 2, 3, 4, 5, 6, 7, 8, 9], $collapsed->all());
    }

    /** @test */
    function can_collapse_array_of_arrays_with_key_values_to_a_flat_array()
    {
        $collection = collect([
            ['name' => 'Sally', 'job' => 'developer'],
            ['school' => 'Arkansas'],
            ['age' => 28]
        ])->collapse();
        $this->assertEquals(["name" => "Sally", "job" => "developer", "school" => "Arkansas", "age" => 28], $collection->all());
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
            return [$name, $age + 1];
        });

        $this->assertEquals([['John Doe', 36], ['Jane Doe', 34]], $collection->all());
    }

    /** @test */
    function can_return_from_eachSpread_by_returning_false()
    {
        $collection = collect([['John Doe', 35], ['Jane Doe', 33]]);

        $collection->eachSpread(function ($name, $age) {
            if ($age == 33) return false;
            return [$name, $age + 1];
        });

        $this->assertEquals([['John Doe', 36], ['Jane Doe', 33]], $collection->all());
    }

    /** @test */
    function can_verify_all_elements_of_a_collection_pass_a_test()
    {
        $ans = collect([1, 2, 3, 4])->every(function ($value, $key) {
            return $value > 2;
        });
        $this->assertFalse($ans);
    }

    /** @test */
    function every_should_return_on_an_empty_collection()
    {
        $collection = collect([]);

        $ans = $collection->every(function ($value, $key) {
            return $value > 2;
        });

        $this->assertTrue($ans);
    }

    /** @test */
    function can_return_all_items_in_collection_except_those_that_are_specified()
    {
        $collection = collect(['product_id' => 1, 'price' => 100, 'discount' => false]);

        $filtered = $collection->except(['price', 'discount']);

        $this->assertEquals(['product_id' => 1], $filtered->all());
    }

    /** @test */
    function can_filter_a_collection_using_a_callback()
    {
        $collection = collect([1, 2, 3, 4]);

        $filtered = $collection->filter(function ($value, $key) {
            return $value > 2;
        });

        $this->assertEquals([3, 4], $filtered->all());
    }

    /** @test */
    function filter_will_remove_falsy_values_in_case_no_callback_supplied()
    {
        $collection = collect([1, 2, 3, null, false, '', 0, []]);

        $this->assertEquals([1, 2, 3], $collection->filter()->all());
    }

    /** @test */
    function can_return_the_first_item_in_array_that_passes_a_test()
    {
        $ans = collect([1, 2, 3, 4])->first(function ($value, $key) {
            return $value > 2;
        });

        $this->assertEquals(3, $ans);
    }

    /** @test */
    function first_will_return_null_on_empty_collection()
    {
        $this->assertNull(collect([])->first(function ($value, $key) {
            return $value > 2;
        }));
    }

    /** @test */
    function first_will_return_the_first_element_in_case_callback_is_empty()
    {
        $this->assertEquals(1, collect([1, 2, 3, 4])->first());
    }

    /** @test */
    function can_filter_elements_in_collection_with_key_value_pair()
    {
        $collection = collect([
            ['name' => 'Regena', 'age' => null],
            ['name' => 'Linda', 'age' => 14],
            ['name' => 'Diego', 'age' => 23],
            ['name' => 'Linda', 'age' => 84],
        ]);

        $this->assertEquals(['name' => 'Linda', 'age' => 14], $collection->firstWhere('name', 'Linda'));
        $this->assertEquals(['name' => 'Linda', 'age' => 84], $collection->firstWhere('age', '>', '83'));
        $this->assertEquals(['name' => 'Linda', 'age' => 84], $collection->firstWhere('age', '>=', '84'));
        $this->assertEquals(['name' => 'Linda', 'age' => 14], $collection->firstWhere('age', '<', '23'));
        $this->assertEquals(['name' => 'Linda', 'age' => 14], $collection->firstWhere('age', '<=', '14'));
    }

    /** @test */
    function when_only_one_parameter_is_given_to_firstWhere_should_return_first_item_where_its_key_is_truthy()
    {
        $collection = collect([
            ['name' => 'Regena', 'age' => null],
            ['name' => 'Linda', 'age' => 14],
            ['name' => 'Diego', 'age' => 23],
            ['name' => 'Linda', 'age' => 84],
        ]);

        $this->assertEquals(['name' => 'Linda', 'age' => 14], $collection->firstWhere('age'));
    }

    /** @test */
    function can_form_new_collection_using_mapping()
    {
        $collection = collect([
            ['name' => 'Sally'],
            ['school' => 'Arkansas'],
            ['age' => 28]
        ]);

        $flattened = $collection->flatMap(function ($values) {
            return array_map('strtoupper', $values);
        });

        $this->assertEquals(['name' => 'SALLY', 'school' => 'ARKANSAS', 'age' => '28'], $flattened->all());
    }

    /** @test */
    function can_flatten_an_array()
    {
        $collection = collect(['name' => 'taylor', 'languages' => ['php', 'javascript']]);

        $flattened = $collection->flatten();

        $this->assertEquals(['taylor', 'php', 'javascript'], $flattened->all());
    }


    /** @test */
    function can_swap_collection_keys_with_values()
    {
        $collection = collect(['name' => 'taylor', 'framework' => 'laravel']);

        $this->assertEquals(['taylor' => 'name', 'laravel' => 'framework'], $collection->flip()->all());
    }

    /** @test */
    function can_remove_an_item_from_collection()
    {
        $collection = collect(['name' => 'taylor', 'framework' => 'laravel']);

        $this->assertEquals(['framework' => 'laravel'], $collection->forget('name')->all());
    }

    /** @test */
    function can_paginate_collection_and_return_the_page_content_based_on_page_number()
    {
        $collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9]);

        $this->assertEquals([4, 5, 6], $collection->forPage(2, 3)->all());
    }

    /** @test */
    function can_get_item_value_by_key()
    {
        $collection = collect(['name' => 'taylor', 'framework' => 'laravel']);

        $this->assertEquals('taylor', $collection->get('name'));
        $this->assertNull($collection->get('doesntexist'));
    }

    /** @test */
    function get_can_return_a_default_value()
    {
        $collection = collect(['name' => 'taylor', 'framework' => 'laravel']);

        $this->assertEquals('default-value', $collection->get('foo', 'default-value'));
    }

    /** @test */
    function get_can_accept_a_callback()
    {
        $collection = collect([]);
        $res = $collection->get('email', function () {
            return 'default-value';
        });

        $this->assertEquals('default-value', $res);
    }

    /** @test */
    function can_group_collection_items_by_a_given_key()
    {
        $collection = collect([
            ['account_id' => 'account-x10', 'product' => 'Chair'],
            ['account_id' => 'account-x10', 'product' => 'Bookcase'],
            ['account_id' => 'account-x11', 'product' => 'Desk'],
        ]);

        $expected = [
            'account-x10' => [
                ['account_id' => 'account-x10', 'product' => 'Chair'],
                ['account_id' => 'account-x10', 'product' => 'Bookcase'],
            ],
            'account-x11' => [
                ['account_id' => 'account-x11', 'product' => 'Desk'],
            ],
        ];

        $this->assertEquals($expected, $collection->groupBy('account_id')->toArray());
    }

    /** @test */
    function groupBy_can_accept_a_callback_instead_of_key()
    {
        $collection = collect([
            ['account_id' => 'account-x10', 'product' => 'Chair'],
            ['account_id' => 'account-x10', 'product' => 'Bookcase'],
            ['account_id' => 'account-x11', 'product' => 'Desk'],
        ]);

        $grouped = $collection->groupBy(function ($item, $key) {
            return substr($item['account_id'], -3);
        });

        $expected =
            [
                'x10' => [
                    ['account_id' => 'account-x10', 'product' => 'Chair'],
                    ['account_id' => 'account-x10', 'product' => 'Bookcase'],
                ],
                'x11' => [
                    ['account_id' => 'account-x11', 'product' => 'Desk'],
                ],
            ];
        $this->assertEquals($expected, $grouped->toArray());
    }

    /** @test */
    function groupBy_can_accept_multiple_grouping_criteria()
    {
        $data = collect([
            10 => ['user' => 1, 'skill' => 1, 'roles' => ['Role_1', 'Role_3']],
            20 => ['user' => 2, 'skill' => 1, 'roles' => ['Role_1', 'Role_2']],
            30 => ['user' => 3, 'skill' => 2, 'roles' => ['Role_1']],
            40 => ['user' => 4, 'skill' => 2, 'roles' => ['Role_2']],
        ]);

        $result = $data->groupBy([
            'skill',
            function ($item) {
                return $item['roles'];
            },
        ], $preserveKeys = true);

        $expected = [
            0 => [
                'Role_1' => [
                    10 => ['user' => 1, 'skill' => 1, 'roles' => ['Role_1', 'Role_3']],
                    20 => ['user' => 2, 'skill' => 1, 'roles' => ['Role_1', 'Role_2']],
                ],
                'Role_2' => [
                    20 => ['user' => 2, 'skill' => 1, 'roles' => ['Role_1', 'Role_2']],
                ],
                'Role_3' => [
                    10 => ['user' => 1, 'skill' => 1, 'roles' => ['Role_1', 'Role_3']],
                ],
            ],
            1 => [
                'Role_1' => [
                    30 => ['user' => 3, 'skill' => 2, 'roles' => ['Role_1']],
                ],
                'Role_2' => [
                    40 => ['user' => 4, 'skill' => 2, 'roles' => ['Role_2']],
                ],
            ],
        ];
       $this->assertEquals($expected,$result->toArray());
    }


}