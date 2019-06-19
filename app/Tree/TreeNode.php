<?php
namespace App\Tree;

class TreeNode{

    public $data = null;
    public $children = [];

    /**
     * TreeNode constructor.
     * @param null $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }


    public function addChildren(TreeNode $treeNode)
    {
       $this->children[] = $treeNode;
    }
}