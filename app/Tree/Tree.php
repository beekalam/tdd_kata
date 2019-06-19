<?php


namespace App\Tree;


class Tree
{
    public $root = null;

    /**
     * Tree constructor.
     * @param null $root
     */
    public function __construct($root)
    {
        $this->root = $root;
    }

    public function traverse(TreeNode $node, int $level = 0)
    {
        if ($node) {
            $ret = str_repeat("-",$level);
            $ret .= $node->data . "\n";

            foreach ($node->children as $childNode) {
                $ret .= $this->traverse($childNode, $level + 1);
            }
            return $ret;
        }
    }


}