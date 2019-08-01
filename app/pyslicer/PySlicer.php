<?php


namespace App\pyslicer;


class PySlicer
{
    /**
     * @var array
     */
    private $arr;
    private $len = 0;

    public function __construct($arr)
    {
        $this->arr = $arr;
        $this->len = count($this->arr);
    }

    public function slicer($start, $end = null, $step = 1)
    {
        if (is_string($start)) {
            list($start, $end, $step) = $this->parseSlice($start);
        } else if (is_null($end)) {
            $end = $this->len;
            return $this->_slice($start, $end, $step);
        }

        return $this->_slice($start, $end, $step);
    }

    private function _slice($start, $end, $step = 1)
    {
        if ($start < 0 || $end < 0 || $step < 0) {
            return $this->sliceNegativeIndexes($start, $end, $step);
        } else {
            return $this->slicePositiveIndexes($start, $end, $step);
        }
    }

    private function slicePositiveIndexes($start, $end, $step = 1)
    {
        $ans = [];
        $len = $this->len;
        for ($i = $start; $i < $end; $i += $step) {
            if ($i < $len) {
                $ans[] = $this->arr[$i];
            }
        }
        return $ans;
    }

    private function sliceNegativeIndexes($start, $end, $step = 1)
    {
        return null;
    }

    private function parseSlice($slice)
    {
        $parts = explode(':', $slice);
        if ($this->hasStartOnly($slice)) {
            $parts[1] = $this->len;
            $parts[2] = 1;
        } else {
            if (count($parts) == 2) {
                $parts[] = 1;
            }
        }
        return $parts;
    }

    private function hasStartOnly($slice)
    {
        $colon_count = substr_count($slice, ":");
        $parts = explode(':', $slice);
        return $colon_count == 1 && empty($parts[1]);
    }

    public static function slice($str, $arr)
    {
        return (new PySlicer($arr))->slicer($str);
    }


}