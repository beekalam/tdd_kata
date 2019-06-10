<?php

namespace Tests\Unit;

use App\Stack\BoundedStack;
use App\Stack\EmptyException;
use App\Stack\IllegalCapacity;
use App\Stack\Overflow;
use App\Stack\Underflow;
use PHPUnit\Framework\TestCase;

class StackTest extends TestCase
{
    /** @var App\Stack */
    private $stack;

    protected function setUp(): void
    {
        parent::setUp();
        $this->stack = BoundedStack::Make(2);
    }

    /** @test */
    function newly_created_stacks_should_be_empty()
    {
        $this->assertTrue($this->stack->isEmpty());
        $this->assertEquals(0, $this->stack->getSize());
    }

    /** @test */
    function after_one_push_stack_size_should_be_one()
    {
        $this->stack->push(1);
        $this->assertEquals(1, $this->stack->getSize());
        $this->assertFalse($this->stack->isEmpty());
    }

    /** @test */
    function after_one_push_and_one_pop_should_be_empty()
    {
        $this->stack->push(1);
        $this->stack->pop();
        $this->assertTrue($this->stack->isEmpty());
    }

    /** @test */
    function when_pushed_past_limit_stack_overflows()
    {
        $this->expectException(Overflow::class);

        $this->stack->push(1);
        $this->stack->push(1);
        $this->stack->push(1);
    }

    /** @test */
    function when_empty_stack_is_popped_should_underflow()
    {
        $this->expectException(Underflow::class);
        $this->stack->pop();
    }

    /** @test */
    function when_one_is_pushed_one_is_popped()
    {
        $this->stack->push(1);
        $this->assertEquals(1, $this->stack->pop());
    }

    /** @test */
    function when_one_and_two_are_pushed_two_and_one_are_popped()
    {
        $this->stack->push(1);
        $this->stack->push(2);
        $this->assertEquals(2, $this->stack->pop());
        $this->assertEquals(1, $this->stack->pop());
    }

    /** @test */
    function when_creating_stack_with_negative_size_should_throw_IllegalCapacity()
    {
        $this->expectException(IllegalCapacity::class);
        BoundedStack::Make(-1);
    }

    /** @test */
    function when_creating_stack_with_zero_capacity_any_push_should_overflow()
    {
        $this->expectException(Overflow::class);
        $stack = BoundedStack::Make(0);
        $stack->push(1);
    }

    /** @test */
    function when_one_is_pushed_one_is_on_top()
    {
        $this->stack->push(1);
        $this->assertEquals(1, $this->stack->top());
    }

    /** @test */
    function when_stack_is_empty_pop_throws_empty()
    {
        $this->expectException(EmptyException::class);
        $this->stack->top();
    }

    /** @test */
    function with_zero_capacity_stack_top_throws_empty()
    {
        $this->expectException(EmptyException::class);
        $stack = BoundedStack::Make(0);
        $stack->top();
    }

    /** @test */
    function given_stack_with_one_two_pushed_find_one_and_two()
    {
        $this->stack->push(1);
        $this->stack->push(2);
        $this->assertEquals(1, $this->stack->find(1));
        $this->assertEquals(0, $this->stack->find(2));
    }

    /** @test */
    function given_stack_with_no_2_find_2_should_return_null()
    {
        $this->assertNull($this->stack->find(2));
    }

}