<?php

namespace App\Queue;

use Exception;

class Queue
{
    private $queue;
    private $capacity;

    public function __construct($capacity)
    {
        $this->queue = [];
        $this->capacity = $capacity;
    }


    public function enqueue(string $newItem)
    {
        if (count($this->queue) < $this->capacity) {
            array_push($this->queue, $newItem);
        } else {
            throw new QueueOverflowException();
        }
    }

    public function dequeue(): string
    {
        if ($this->isEmpty()) {
            throw new QueueUnderflowException();
        }
        return array_shift($this->queue);
    }


    public function peek(): string
    {
        return current($this->queue);
    }

    public function isEmpty(): bool
    {
        return empty($this->queue);
    }
}

class QueueUnderflowException extends Exception
{
}

class QueueOverflowException extends Exception
{
}
