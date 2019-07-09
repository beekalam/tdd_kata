<?php


namespace App\collections;


class Collection
{
    private $arr;

    /**
     * Collection constructor.
     */
    public function __construct($array)
    {
        $this->arr = $array;
    }

    public function all()
    {
        return $this->arr;
    }

    public function average($key = null)
    {
        return $this->avg($key);
    }

    public function avg($key = null)
    {
        if (is_null($key)) {
            return array_sum($this->arr) / count($this->arr);
        } else {
            $sum = 0;
            foreach ($this->arr as $row) {
                if (!isset($row[$key]))
                    throw new CollectionException("key {$key} does not exist");
                $sum += $row[$key];
            }
            return $sum / count($this->arr);
        }
        return 0;
    }

    public function chunk($size)
    {
        $ans = [];
        $chunk = [];
        foreach ($this->arr as $row) {
            if (count($chunk) == $size) {
                $ans[] = $chunk;
                $chunk = [];
            }
            $chunk[] = $row;

        }
        if (count($chunk) > 0) $ans[] = $chunk;
        return collect($ans);
    }

    public function collapse()
    {
        return collect(flattenArray($this->arr));
    }

    public function combine($toCombine)
    {
        $res = [];
        for ($i = 0; $i < count($this->arr); $i++) {
            $res[$this->arr[$i]] = $toCombine[$i];
        }
        return collect($res);
    }

    public function concat($toConcat)
    {
        if ($toConcat instanceof Collection)
            $toConcat = $toConcat->toArray();
        $res = $this->arr;
        if (isAssociative($toConcat)) {
            $toConcat = array_values($toConcat);
        }
        foreach (array_values($toConcat) as $v) $res[] = $v;
        return collect($res);
    }

    public function contains($key, $value = null)
    {
        if (is_callable($key)) {
            return $this->searchWithCallable($key);
        } else if (is_null($value)) {
            return $this->searchFlatArray($key);
        } else {
            return $this->searchKeyValuePair($key, $value);
        }
    }

    public function count()
    {
        return count($this->arr);
    }

    function countBy($callable = null)
    {
        if (is_callable($callable)) {
            $ans = [];
            foreach ($this->arr as $row) {
                $res = $callable($row);
                if ($res) {
                    $ans[] = $res;
                }
            }
            return collect(array_count_values($ans));
        }

        return collect(array_count_values($this->arr));
    }

    public function crossJoin(...$params)
    {
        $src = $this->arr;
        foreach ($params as $arr) {
            $src = $this->_crossJoin($src, $arr)->toArray();
        }
        return collect($src);
    }

    private function _crossJoin($src, $arr)
    {
        $ans = [];
        foreach ($src as $a) {
            foreach ($arr as $b) {
                if (is_array($a)) {
                    $res = $a;
                    $res[] = $b;
                    $ans[] = $res;
                } else {
                    $ans[] = [$a, $b];
                }
            }
        }
        return collect($ans);
    }

    public function diff($arr)
    {
        return collect(array_values(array_diff(array_values($this->arr),array_values($arr))));
    }

    public function diffAssoc($arr)
    {
       return collect(array_diff_assoc($this->arr,$arr));
    }

    public function toArray()
    {
        return $this->arr;
    }

    /**
     * @param $key
     * @return bool
     */
    private function searchFlatArray($key)
    {
        if (is_array($this->arr) && isAssociative($this->arr))
            $haystack = array_values($this->arr);
        else
            $haystack = $this->arr;

        return in_array($key, $haystack);
    }

    /**
     * @param $key
     * @param $value
     * @return bool
     * @throws CollectionException
     */
    private function searchKeyValuePair($key, $value)
    {
        foreach ($this->arr as $row) {
            if (!is_array($row) || !isset($row[$key])) {
                throw new CollectionException("input does not  contain array or key {$key} is not set");
            }
            if (isset($row[$key]) && $row[$key] == $value) return true;
        }
        return false;
    }

    /**
     * @param $key
     * @return bool
     */
    private function searchWithCallable($key)
    {
        foreach ($this->arr as $k => $v) {
            if ($key($v, $k)) return true;
        }
        return false;
    }

}

class CollectionException extends \Exception
{

}
