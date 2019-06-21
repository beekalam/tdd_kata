<?php


namespace App\Tree;


class BinaryTree
{
    public $root = null;

    /**
     * BinaryTree constructor.
     * @param BinaryNode $root
     */
    public function __construct(BinaryNode $root)
    {
        $this->root = $root;
    }

    public function traverse(BinaryNode $node, int $level = 0)
    {
        $ret = "";
        if($node){
            $ret .= str_repeat("-",$level);
            $ret .= $node->data . "\n";

            if($node->left){
                $ret .= $this->traverse($node->left, $level + 1);
            }

            if($node->right){
                $ret .= $this->traverse($node->right, $level+1);
            }

            return $ret;
        }
    }




}