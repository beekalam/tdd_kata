<?php


namespace App\Stack;


use Exception;

class BoundedStack implements Stack
{

    private $size = 0;
    private $capacity;
    private $element;

    public static function Make($capacity)
    {
        if ($capacity < 0)
            throw new IllegalCapacity();
        if ($capacity == 0)
            return new ZeroCapacityStack();
        return new BoundedStack($capacity);
    }

    private function __construct($capacity)
    {
        $this->capacity = $capacity;
        $this->element = new \SplFixedArray($this->capacity);
    }

    public function isEmpty()
    {
        return $this->size == 0;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function push($element)
    {
        if ($this->size == $this->capacity)
            throw new Overflow();
        $this->element[$this->size] = $element;
        $this->size++;
    }

    public function pop()
    {
        if ($this->isEmpty())
            throw new Underflow();
        $this->size--;
        return $this->element[$this->size];
    }

    public function top()
    {
        if ($this->isEmpty())
            throw new EmptyException();
        return $this->element[$this->size - 1];
    }

    public function find($element)
    {
        for ($i = $this->size - 1; $i >= 0; $i--) {
            if ($this->element[$i] == $element)
                return ($this->size - 1) - $i;
        }
        return null;
    }
}

class ZeroCapacityStack implements Stack
{

    public function isEmpty()
    {
        return true;
    }

    public function getSize()
    {
        return size;
    }

    public function push($element)
    {
        throw new Overflow();
    }

    public function pop()
    {
        throw new Underflow();
    }

    public function top()
    {
        throw new EmptyException();
    }

    public function find()
    {
        return null;
    }
}
