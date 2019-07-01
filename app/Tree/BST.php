<?php


namespace App\Tree;


class BST
{
    /**
     * @var BSTNodef
     */
    public $root = null;
    public $nodeCount = 0;

    public function __construct(int $data)
    {
        $this->root = new BSTNode($data);
        $this->nodeCount = 1;
    }

    public function isEmpty(): bool
    {
        return $this->root === null;
    }

    function insert(int $data)
    {
        if ($this->isEmpty()) {
            $node = new BSTNode($data);
            $this->root = $node;
            return $node;
        }

        $node = $this->root;
        while ($node) {
            if ($data > $node->data) {
                if ($node->right) {
                    $node = $node->right;
                } else {
                    $node->right = new BSTNode($data, $node);
                    $node = $node->right;
                    $this->nodeCount++;
                    break;
                }
            } elseif ($data < $node->data) {
                if ($node->left) {
                    $node = $node->left;
                } else {
                    $node->left = new BSTNode($data, $node);
                    $node = $node->left;
                    $this->nodeCount++;
                    break;
                }
            } else {
                break;
            }
        }

        return $node;
    }

    public function search($value)
    {
        if ($this->isEmpty()) return false;
        $node = $this->root;
        while ($node) {
            if ($value > $node->data) {
                $node = $node->right;
            } elseif ($value < $node->data) {
                $node = $node->left;
            } else {
                break;
            }
        }
        return $node;
    }

    public function remove(int $data)
    {
        $node = $this->search($data);
        if ($node) {
            $node->delete();
            $this->nodeCount--;
        }
    }

    public function traverse(BSTNode $node)
    {
        if ($node) {
            if ($node->left)
                $this->traverse($node->left);
            echo $node->data . "\n";
            if ($node->right)
                $this->traverse($node->right);
        }
    }


}
