<?php

namespace App\Linkedlist;

class ListNode{
    public $data = null;
    public $next = null;

    public function __construct($data)
    {
        $this->data = $data;
    }
}