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
        return collect(array_values(array_diff(array_values($this->arr), array_values($arr))));
    }

    public function diffAssoc($arr)
    {
        return collect(array_diff_assoc($this->arr, $arr));
    }

    public function diffKeys($arr)
    {
        return collect(array_diff_key($this->arr, $arr));
    }

    public function duplicates($key = null)
    {
        $to_filter = is_null($key) ? $this->arr : array_column($this->arr, $key);

        $ans = array_filter(array_count_values($to_filter), function ($v, $k) {
            return $v > 1;
        }, ARRAY_FILTER_USE_BOTH);

        return array_keys($ans);
    }

    public function each($callable)
    {
        $arr = [];
        $cancelled = false;
        foreach ($this->arr as $key => $item) {
            if ($cancelled) {
                $arr[$key] = $item;
            } else {
                $cr = $callable($item, $key);
                if ($cr === false) {
                    $arr[$key] = $item;
                    $cancelled = true;
                } else {
                    $arr[$key] = $cr;
                }
            }
        }
        $this->arr = $arr;
        return $this;
    }

    public function eachSpread($callable)
    {
        $arr = [];
        $cancelled = false;
        foreach ($this->arr as $row) {
            if ($cancelled) {
                $arr[] = $row;
            } else {
                if (call_user_func_array($callable, $row) === false) {
                    $cancelled = true;
                    $arr[] = $row;
                } else {
                    $arr[] = call_user_func_array($callable, $row);
                }
            }
        }
        $this->arr = $arr;
        return $this;
    }

    public function every($callable)
    {
        foreach ($this->arr as $k => $v) {
            if (!$callable($v, $k)) return false;
        }
        return true;
    }

    public function except($arr)
    {
        $ans = [];
        foreach ($this->arr as $k => $v) {
            if (!in_array($k, $arr)) {
                $ans[$k] = $v;
            }
        }
        return collect($ans);
    }

    public function filter($callable = null)
    {
        $ans = is_null($callable) ? $this->filterNotFalsy() : $this->filterWithCallback($callable);
        return collect($ans);
    }

    /**
     * @param array $ans
     * @return array
     */
    private function filterNotFalsy()
    {
        $ans = [];
        foreach ($this->arr as $k => $v) {
            if ($this->isFalsy($v)) {
                $ans[] = $v;
            }
        }
        return $ans;
    }

    /**
     * @param       $callable
     * @return array
     */
    private function filterWithCallback($callable)
    {
        $ans = [];
        foreach ($this->arr as $k => $v) {
            if ($callable($v, $k)) {
                $ans[] = $v;
            }
        }
        return $ans;
    }

    private function isFalsy($v)
    {
        return (boolean)$v;
    }

    public function first($callable = null)
    {
        if (is_null($callable) && count($this->arr) > 0) {
            return $this->arr[0];
        } else {
            foreach ($this->arr as $k => $v) {
                if ($callable($v, $k)) return $v;
            }
        }
        return null;
    }

    public function firstWhere()
    {
        if (func_num_args() == 2) {
            return $this->_firstWhere(func_get_arg(0), '=', func_get_arg(1));
        } elseif (func_num_args() == 3) {
            return $this->_firstWhere(func_get_arg(0), func_get_arg(1), func_get_arg(2));
        } else if (func_num_args() == 1) {
            $expected_key = func_get_arg(0);
            foreach ($this->arr as $row) {
                if (isset($row[$expected_key]) && $row[$expected_key])
                    return $row;
            }
            return null;
        }
        return null;
    }

    private function _firstWhere($expected_key, $operator, $expected_value)
    {
        foreach ($this->arr as $row) {
            if (isset($row[$expected_key])) {
                if ($operator == '=' && $row[$expected_key] == $expected_value) {
                    return $row;
                } else if ($operator == '>' && $row[$expected_key] > $expected_value) {
                    return $row;
                } elseif ($operator == '>=' && $row[$expected_key] >= $expected_value) {
                    return $row;
                } elseif ($operator == '<' && $row[$expected_key] < $expected_value) {
                    return $row;
                } elseif ($operator == '<=' && $row[$expected_key] <= $expected_value) {
                    return $row;
                }
            }
        }

        return null;
    }

    public function flatMap($callable)
    {
        $keys = array_keys($this->arr);
        $items = array_map($callable, $this->arr, $keys);
        $ans = array_combine($keys, $items);
        return collect(collect($ans)->collapse()->all());
    }

    public function flatten()
    {
        $ans = flattenArray($this->arr);
        return collect(array_values($ans));
    }

    public function flip()
    {
        return collect(array_flip($this->arr));
    }

    public function forget($key)
    {
        $ans = [];
        foreach ($this->arr as $k => $v) {
            if ($key !== $k) {
                $ans[$k] = $v;
            }
        }
        return collect($ans);
    }

    public function forPage($page, $size)
    {
        $index = ($page - 1) * $size;
        if ($index + $size < count($this->arr)) {
            return collect(array_splice($this->arr, $index, $size));
        } else {
            return collect([]);
        }
    }

    public function get($key, $default = null)
    {
        if (!isset($this->arr[$key])) {
            return is_callable($default) ? $default() : $default;
        }
        return $this->arr[$key];
    }

    // public function groupBy($toIndex)
    // {
    //     if (func_num_args() == 1) {
    //         if (is_callable($toIndex)) {
    //             return $this->_groupByCallback($toIndex);
    //         } else {
    //             return $this->_groupBy(array_unique(array_column($this->arr, $toIndex)), $toIndex);
    //         }
    //     } elseif (func_num_args() == 2) {
    //         $criterias = func_get_arg(0);
    //         return $this->_multipleGroupBy($criterias[0], $criterias[1], func_get_arg(1));
    //     }
    // }
    //
    // private function _groupBy($keys, $toIndex)
    // {
    //     $ans = [];
    //     foreach ($keys as $key) {
    //         foreach ($this->arr as $row) {
    //             if (isset($row[$toIndex]) && $row[$toIndex] == $key) $ans[$key][] = $row;
    //         }
    //     }
    //     return collect($ans);
    // }
    //
    // private function _groupByCallback($callback)
    // {
    //     $ans = [];
    //     foreach ($this->arr as $k => $row) {
    //         $toIndex = $callback($row, $k);
    //         $ans[$toIndex][] = $row;
    //     }
    //     return collect($ans);
    // }
    //
    // private function _multipleGroupBy($toIndex, $callback, $preserveKeys = true)
    // {
    //     var_dump($toIndex);
    //     $groupedByIndex = $this->_groupBy(array_unique(array_column($this->arr, $toIndex)), $toIndex);
    //     print_r($groupedByIndex->toArray());
    // }

    // private function getClosureParameters($closure)
    // {
    //     $arguments = (new \ReflectionFunction($closure))->getParameters();
    //     $params = [];
    //     foreach ($arguments as $arg) {
    //         $params[] = $arg->name;
    //     }
    //     return $params;
    // }


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
