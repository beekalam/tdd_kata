<?php

namespace Tests\Unit;

use App\Queue\Queue;
use App\Queue\QueueOverflowException;
use App\Queue\QueueUnderflowException;
use PHPUnit\Framework\TestCase;

class QueueTest extends TestCase
{
    private $queue;

    protected function setUp(): void
    {
        parent::setUp();
        $this->queue = new Queue(10);
    }

    /** @test */
    function when_item_equeued_can_get_item_with_peek()
    {
        $this->queue->enqueue(1);
        $this->assertEquals(1, $this->queue->peek());
    }

    /** @test */
    function when_item_equeued_should_get_item_with_deque()
    {
        $this->queue->enqueue(1);
        $this->assertEquals(1, $this->queue->dequeue());
    }

    /** @test */
    function when_queue_is_empty_dequeue_should_throw_exception()
    {
        $this->expectException(QueueUnderflowException::class);
        $this->queue->dequeue();
    }

    /** @test */
    function when_equeued_more_than_capacity_of_queue_should_throw_exception()
    {
        $this->expectException(QueueOverflowException::class);
        $queue = new Queue(2);
        $queue->enqueue(1);
        $queue->enqueue(2);
        $queue->enqueue(3);
    }
}