<?php


namespace App\pyslicer;


class PySlicer
{
    /**
     * @var array
     */
    private $arr;

    public function __construct($arr)
    {
        $this->arr = $arr;
    }

    public function _slice($start, $end = 0, $step = 1)
    {
        if (is_string($start)) {
            list($start, $end, $step) = $this->parseSlice($start);
            return $this->__slice(intval($start), intval($end), intval($step));
        } else {
            return $this->__slice($start, $end, $step);
        }
    }

    private function __slice($start, $end, $step = 1)
    {
        $ans = [];
        $len = count($this->arr);
        for ($i = $start; $i < $end; $i += $step) {
            if ($i < $len) {
                $ans[] = $this->arr[$i];
            }
        }
        return $ans;
    }

    private function parseSlice($slice)
    {
        $parts = explode(':', $slice);
        if (count($parts) == 2) {
            $parts[] = 1;
        }
        // var_dump($parts);
        return $parts;
    }

    public static function slice($str, $arr)
    {
        return (new PySlicer($arr))->_slice($str);
    }


}