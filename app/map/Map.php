<?php


namespace App\map;


class Map
{

    private $size;
    private $elements;

    public function __construct()
    {
        $this->elements = [];
    }

    public function size()
    {
        return $this->size;
    }

    public function put($key, $value)
    {
        $this->elements[$key] = $value;
        $this->size++;
    }

    public function get($key)
    {
        if (isset($this->elements[$key]))
            return $this->elements[$key];
        return null;
    }

    public function remove($key)
    {
        if (isset($this->elements[$key])) {
            unset($this->elements[$key]);
            $this->size--;
        }
    }

    public function clear()
    {
        $this->elements = [];
        $this->size = 0;
    }
}