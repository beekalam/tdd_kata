<?php

namespace App\Set;

class  Set
{

    private $members;

    public function __construct($init = null)
    {
        $this->members = [];
        if (is_array($init)) {
            $this->add($init);
        }
    }

    public function add($member)
    {
        if (is_array($member)) {
            foreach ($member as $m) {
                $this->addMember($m);
            }
        } else {
            $this->addMember($member);
        }
    }

    public function size()
    {
        return count($this->members);
    }

    public function contains($member)
    {
        return $this->hasMember($member);
    }

    public function remove($member)
    {
        $key = array_search($member, $this->members);
        if ($key !== false) {
            unset($this->members[$key]);
        }
    }

    public function isEmpty()
    {
        return $this->size() == 0;
    }

    /**
     * @param $member
     * @return bool
     */
    private function hasMember($member)
    {
        return in_array($member, $this->members);
    }

    public static function union(Set $set1, Set $set2)
    {
        $set = new Set();
        $set->add($set1->toArray());
        $set->add($set2->toArray());
        return $set;
    }

    public static function intersect(Set $set1, Set $set2)
    {
        $intersection = array_intersect($set1->toArray(), $set2->toArray());
        return new Set($intersection);
    }

    public function toArray()
    {
        return $this->members;
    }

    /**
     * @param $member
     */
    private function addMember($member)
    {
        if (!$this->hasMember($member))
            array_push($this->members, $member);
    }
}