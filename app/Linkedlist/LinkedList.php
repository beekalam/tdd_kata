<?php

namespace App\Linkedlist;

use App\Linkedlist\ListNode;
use Iterator;
class LinkedList implements Iterator
{
    private $_firstNode = null;
    private $_total = 0;
    private $_currentNode = null;
    private $_currentPosition = 0;


    public function __construct()
    {
    }

    public function getFirstNode()
    {
        return $this->_firstNode;
    }

    public function getTotal()
    {
        return $this->_total;
    }

    public function insert($data)
    {
        $newNode = new ListNode($data);
        if ($this->_firstNode == null) {
            $this->_firstNode = &$newNode;
        } else {
            $this->lastNode()->next = &$newNode;
        }

        $this->_total++;
    }

    public function lastNode()
    {
        $current_node = $this->_firstNode;
        while ($current_node->next !== null) {
            $current_node = $current_node->next;
        }
        return $current_node;
    }

    public function insertAtFirst($data)
    {
        $newNode = new ListNode($data);
        if ($this->_firstNode == null) {
            $this->_firstNode = &$newNode;
        } else {
            $currentFirstNode = $this->_firstNode;
            $this->_firstNode =& $newNode;
            $newNode->next = $currentFirstNode;
        }
        $this->_total++;
    }

    public function insertBefore($data, $query)
    {
        $newNode = new ListNode($data);
        if ($this->_firstNode) {
            $previous = null;
            $current_node = $this->_firstNode;
            while ($current_node !== null) {
                if ($current_node->data == $query) {
                    $newNode->next = $current_node;
                    $previous->next = $newNode;
                    $this->_total++;
                    break;
                }
                $previous = $current_node;
                $current_node = $current_node->next;
            }
        }
    }

    public function insertAfter($data, $query)
    {
        $newNode = new ListNode($data);
        if ($this->_firstNode) {
            $current_node = $this->_firstNode;
            while ($current_node !== null) {
                if ($current_node->data == $query) {
                    if ($current_node->next !== null) {
                        $newNode->next = $current_node->next;
                        $current_node->next = $newNode;
                    } else {
                        $current_node->next = $newNode;
                    }
                    $this->_total++;
                    break;
                }
                $current_node = $current_node->next;
            }
        }
    }

    public function deleteFirst()
    {
        if ($this->_firstNode) {
            if ($this->_firstNode->next === null)
                $this->_firstNode = null;
            else {
                $this->_firstNode = $this->_firstNode->next;
            }
            $this->_total--;
        }
    }

    public function deleteLast()
    {
        if ($this->_firstNode) {
            if ($this->_firstNode->next === null) {
                $this->_total--;
                $this->_firstNode = null;
                return;
            }
            $prev = null;
            $current_node = $this->_firstNode;
            while ($current_node->next !== null) {
                $prev = $current_node;
                $current_node = $current_node->next;
            }
            $prev->next = null;
            $this->_total--;
        }
    }

    public function delete($query)
    {
        if ($this->_firstNode) {
            if ($this->_firstNode->data == $query) {
                $this->deleteFirst();
                return;
            }
            if ($this->_firstNode->next == null) return;
            $prev = $this->_firstNode;
            $current_node = $this->_firstNode->next;
            while ($current_node->next !== null) {
                if ($current_node->data == $query) {
                    $prev->next = $current_node->next;
                    $this->_total--;
                    return;
                }
                $prev = $current_node;
                $current_node = $current_node->next;
            }
        }
    }

    public function reverse()
    {
        $reversed = new LinkedList();
        if ($this->_firstNode === null) return $reversed;
        $current_node = $this->_firstNode;
        $reversed->insert($current_node->data);
        $current_node = $current_node->next;
        while ($current_node !== null) {
            $reversed->insertAtFirst($current_node->data);
            $current_node = $current_node->next;
        }
        return $reversed;
    }

    public function getNthNode($n = 0)
    {
        $index = 0;
        if ($this->_firstNode) {
            $current_node = $this->_firstNode;
            while ($current_node !== null) {
                if ($index == $n) {
                    return $current_node;
                }
                $index++;
                $current_node = $current_node->next;
            }
        }
        return null;
    }

    public function find($data)
    {
        if ($this->getTotal()) {
            $current_node = $this->_firstNode;
            while ($current_node !== null) {
                if ($current_node->data == $data) {
                    return $current_node;
                }

                $current_node = $current_node->next;
            }
        }

        return null;
    }

    public function display()
    {
        $str = "Total items: {$this->getTotal()}\n";
        $current_node = $this->_firstNode;
        while ($current_node->next !== null) {
            $str .= $current_node->data . "\n";
            $current_node = $current_node->next;
        }
        return $str;
    }

    public function current()
    {
        return $this->_currentNode->data;
    }

    public function next()
    {
        $this->_currentPosition++;
        $this->_currentNode = $this->_currentNode->next;
    }

    public function key()
    {
        return $this->_currentPosition;
    }

    public function valid()
    {
        return $this->_currentNode !== null;
    }

    public function rewind()
    {
        $this->_currentPosition = 0;
        $this->_currentNode = $this->_firstNode;
    }
}
