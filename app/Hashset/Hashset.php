<?php


namespace App\Hashset;

/*
Design a HashSet without using any built-in hash table libraries.

To be specific, your design should include these functions:

add(value): Insert a value into the HashSet.
contains(value) : Return whether the value exists in the HashSet or not.
remove(value): Remove a value in the HashSet. If the value does not exist in the HashSet, do nothing.
 */

use SplFixedArray;

class Hashset
{
    private $values;
    private $count;
    private $current_index = 0;
    private $null_counts = 0;

    public function __construct()
    {
        $this->count = 4;
        $this->values = new SplFixedArray($this->count);
    }


    public function add($value)
    {
        if (!$this->contains($value)) {
            if ($this->current_index == $this->count) {
                $this->count *= 2;
                $this->values->setSize($this->count);
            }

            $this->values[$this->current_index] = $value;
            $this->current_index++;
        }
    }

    public function contains($value)
    {
        for ($i = 0; $i < $this->count; $i++) {
            if ($this->values[$i] == $value) {
                return true;
            }
        }

        return false;
    }

    public function remove($value)
    {
        if ($this->contains($value)) {
            for ($i = 0 ; $i < $this->count; $i++) {
                if($this->values[$i] == $value){
                    $this->values[$i] = null;
                    $this->null_counts++;
                }
            }
        }
    }

    public function size()
    {
        $size = $this->current_index - $this->null_counts;
        return $size;
    }
}