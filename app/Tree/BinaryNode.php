<?php


namespace App\Tree;


class BinaryNode
{
    public $data;
    public $left;
    public $right;

    /**
     * BinaryNode constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function addChildren(BinaryNode $left, BinaryNode $right)
    {
        $this->left = $left;
        $this->right = $right;
    }
}