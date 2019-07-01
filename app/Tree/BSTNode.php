<?php


namespace App\Tree;


class BSTNode
{
    public $left = null;
    public $right = null;
    public $data = null;
    public $parent = null;

    /**
     * BSTNode constructor.
     * @param null $data
     */
    public function __construct($data, BSTNode $parent = null)
    {
        $this->data = $data;
        $this->parent = $parent;
    }

    public function min()
    {
        $node = $this;
        while ($node->left) {
            $node = $node->left;
        }
        return $node->data;
    }

    public function max()
    {
        $node = $this;
        while ($node->right) {
            $node = $node->right;
        }
        return $node->data;
    }

    public function successor()
    {
        $node = $this;
        if ($node->right) {
            return $node->right->min();
        } else {
            return null;
        }
    }

    public function predecessor()
    {
        $node = $this;
        if ($node->left) {
            return $node->left->max();
        } else {
            return null;
        }
    }

    public function delete()
    {
        $node = $this;
        if (!$node->left && !$node->right) {
            if ($node->parent->left === $node) {
                $node->parent->left = NULL;
            } else {
                $node->parent->right = NULL;
            }
        } elseif ($node->left && $node->right) {
            $successor = $node->successor();
            $node->data = $successor->data;
            $successor->delete();
        } elseif ($node->left) {
            if ($node->parent->left === $node) {
                $node->parent->left = $node->left;
                $node->left->parent = $node->parent->left;
            } else {
                $node->parent->right = $node->left;
                $node->left->parent = $node->parent->right;
            }
            $node->left = NULL;
        } elseif ($node->right) {

            if ($node->parent->left === $node) {
                $node->parent->left = $node->right;
                $node->right->parent = $node->parent->left;
            } else {
                $node->parent->right = $node->right;
                $node->right->parent = $node->parent->right;
            }
            $node->right = NULL;
        }

    }


}