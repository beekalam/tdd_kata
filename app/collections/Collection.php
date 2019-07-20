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

    public function groupBy($toIndex)
    {
        if (func_num_args() == 1) {
            if (is_callable($toIndex)) {
                return $this->_groupByCallback($toIndex);
            } else {
                return $this->_groupBy(array_unique(array_column($this->arr, $toIndex)), $toIndex);
            }
        } elseif (func_num_args() == 2) {
            $criterias = func_get_arg(0);
            return $this->_multipleGroupBy($criterias[0], $criterias[1], func_get_arg(1));
        }
    }

    private function _groupBy($keys, $toIndex)
    {
        $ans = [];
        foreach ($keys as $key) {
            foreach ($this->arr as $row) {
                if (isset($row[$toIndex]) && $row[$toIndex] == $key) $ans[$key][] = $row;
            }
        }
        return collect($ans);
    }

    public function _groupByCallback($callback, $preserveKeys = false)
    {
        $ans = [];
        foreach ($this->arr as $k => $row) {
            $toIndex = $callback($row, $k);
            if (is_array($toIndex)) {
                foreach ($toIndex as $key => $value) {
                    if ($preserveKeys && isset($row['__key__'])) {
                        $__key__ = $row['__key__'];
                        $ans[$value][$__key__] = $row;
                    } else {
                        $ans[$value][] = $row;
                    }
                }
            } else {
                $ans[$toIndex][] = $row;
            }

        }
        return collect($ans);
    }

    private function _multipleGroupBy($toIndex, $callback, $preserveKeys = true)
    {
        foreach ($this->arr as $k => &$v) {
            $v['__key__'] = $k;
        }
        $groupedByIndex = $this->_groupBy(array_unique(array_column($this->arr, $toIndex)), $toIndex)->toArray();
        $ans = [];
        foreach ($groupedByIndex as $k => $v) {
            $ans[] = collect($v)->_groupByCallback($callback, true)->toArray();
        }
        $this->arrayRemoveKey($ans, '__key__');
        return collect($ans);
    }

    private function arrayRemoveKey(&$arr, $key)
    {
        foreach ($arr as $k => &$v) {
            if (is_array($v)) {
                $this->arrayRemoveKey($v, $key);
            }
            if ($k == $key && isset($arr[$key])) {
                unset($arr[$key]);
            }
        }
    }

    public function has($args)
    {
        if (is_array($args)) {
            return $this->_hasKeyInArray($args);
        } else {
            return $this->_has($args);
        }
    }

    private function _has($key)
    {
        return array_key_exists($key, $this->arr);
    }

    /**
     * @param $args
     * @return bool
     */
    private function _hasKeyInArray($args)
    {
        foreach ($args as $key) {
            if (!$this->_has($key))
                return false;
        }
        return true;
    }

    public function implode()
    {
        if (func_num_args() == 1) {
            $glue = func_get_arg(0);
            return implode($glue, $this->arr);
        } elseif (func_num_args() == 2) {
            $key = func_get_arg(0);
            $glue = func_get_arg(1);
            return implode($glue, array_column($this->arr, $key));
        }
    }

    public function intersect($arr)
    {
        $ans = array_intersect($this->arr, $arr);
        return collect($ans);
    }

    public function intersectByKeys($arr)
    {
        $ans = array_intersect_key($this->arr, $arr);
        return collect($ans);
    }

    public function isEmpty()
    {
        return empty($this->arr);
    }

    public function isNotEmpty()
    {
        return !empty($this->arr);
    }

    public function join($glue, $glue2 = null)
    {
        return is_null($glue2) ? implode($glue, $this->arr) : $this->_join($glue, $glue2);
    }

    /**
     * @param $glue
     * @param $glue2
     * @return string
     */
    private function _join($glue, $glue2)
    {
        $len = count($this->arr);
        if ($len == 1) return $this->arr[0];

        $ret = '';
        for ($i = 0; $i < $len; $i++) {
            if ($i == $len - 1) {
                $ret .= $glue2 . $this->arr[$i];
            } elseif ($i == $len - 2) {
                $ret .= $this->arr[$i];
            } else {
                $ret .= $this->arr[$i] . $glue;
            }
        }
        return $ret;
    }

    public function keyBy($key)
    {
        return is_callable($key) ? $this->keyByCallback($key) : $this->_keyBy($key);
    }

    public function keyByCallback($callback)
    {
        $ans = [];
        foreach ($this->arr as $row) {
            $computed_key = $callback($row);
            $ans[$computed_key] = $row;
        }
        return collect($ans);
    }

    private function _keyBy($key)
    {
        $ans = [];
        foreach ($this->arr as $row) {
            if (isset($row[$key])) {
                $ans[$row[$key]] = $row;
            }
        }
        return collect($ans);
    }

    public function keys()
    {
        return collect(array_keys($this->arr));
    }


    public function last($callable = null)
    {
        if (is_null($callable)) {
            return end($this->arr);
        }

        $ans = null;
        foreach ($this->arr as $k => $v) {
            if ($callable($v, $k) === true) {
                $ans = $v;
            }
        }
        return $ans;
    }

    public function map($callable)
    {
        $ans = [];
        foreach ($this->arr as $k => $v) {
            $ans[] = $callable($v, $k);
        }
        return collect($ans);
    }

    public function mapSpread($callable)
    {
        $ans = [];
        foreach ($this->arr as $row) {
            $ans[] = call_user_func_array($callable, $row);
        }
        return collect($ans);
    }

    public function mapToGroups($callable)
    {
        $ans = [];
        foreach ($this->arr as $k => $row) {
            foreach ($callable($row, $k) as $key => $value) {
                $ans[$key][] = $value;
            }
        }
        return collect($ans);
    }

    public function mapWithKeys($callable)
    {
        $ans = [];
        foreach ($this->arr as $k => $row) {
            foreach ($callable($row, $k) as $key => $value) {
                $ans[$key] = $value;
            }
        }
        return collect($ans);
    }

    public function max($key = null)
    {
        return is_null($key) ? max($this->arr) : max(array_column($this->arr, $key));
    }

    public function median($key = null)
    {
        return is_null($key) ? $this->_median($this->arr) : $this->_median(array_column($this->arr, $key));
    }

    private function _median($arr)
    {
        $len = count($arr);
        return count($arr) % 2 == 1 ? $arr[$len / 2] : ($arr[$len / 2] + $arr[($len / 2 - 1)]) / 2.0;
    }

    public function merge($arr)
    {
        return collect(array_merge($this->arr, $arr));
    }

    public function mergeRecursive($arr)
    {
        foreach ($arr as $k => $v) {
            if (isset($this->arr[$k])) {
                $this->arr[$k] = [$this->arr[$k], $v];
            } else {
                $this->arr[$k] = $v;
            }
        }
        return collect($this->arr);
    }

    public function min($key = null)
    {
        return is_null($key) ? min($this->arr) : min(array_column($this->arr, $key));
    }

    public function mode($key = null)
    {
        return is_null($key) ? $this->_mode($this->arr) : $this->_mode(array_column($this->arr, $key));
    }

    private function _mode($arr)
    {
        $arr = array_count_values($arr);
        asort($arr, SORT_NUMERIC);
        $arr = array_flip($arr);
        return end($arr);
    }

    public function nth($n, $offset = 0)
    {
        $len = count($this->arr);
        $ans = [];
        for ($i = $offset; $i < $len; $i += $n) {
            if ($i < $len) {
                $ans[] = $this->arr[$i];
            }
        }
        return $ans;
    }

    public function only($arr)
    {
        $ans = [];
        foreach ($arr as $key) {
            if (isset($this->arr[$key])) {
                $ans[$key] = $this->arr[$key];
            }
        }
        return $ans;
    }

    public function pad($size, $padding)
    {
        $ans = $this->arr;
        if (abs($size) > count($this->arr)) {
            $ans = array_pad($ans, $size, $padding);
        }
        return collect($ans);
    }

    public function partition($callable)
    {
        $ans = [];
        foreach ($this->arr as $v) {
            if ($callable($v)) {
                $ans[0][] = $v;
            } else {
                $ans[1][] = $v;
            }
        }
        return [collect($ans[0]), collect($ans[1])];
    }

    public function pipe($callable)
    {
        return $callable($this);
    }

    public function pluck($key, $result_key = null)
    {
        if (is_null($result_key)) {
            return collect(array_column($this->arr, $key));
        } else {
            return collect(
                array_combine(
                    array_column($this->arr, $result_key),
                    array_column($this->arr, $key)
                )
            );
        }
    }

    public function pop()
    {
        return array_pop($this->arr);
    }

    public function prepend($value, $key = null)
    {
        if (is_null($key)) {
            array_unshift($this->arr, $value);
        } else {
            $this->arr = [$key => $value] + $this->arr;
        }
        return $this;
    }

    public function pull($key)
    {
        if (isset($this->arr[$key])) {
            $value = $this->arr[$key];
            unset($this->arr[$key]);
            return $value;
        }
        return null;
    }

    public function push($value)
    {
        array_push($this->arr, $value);
        return $this;
    }

    public function put($key, $value)
    {
        $this->arr[$key] = $value;
        return $this;
    }

    public function random($numberOfRandomItems = 1)
    {
        if ($numberOfRandomItems == 1) {
            return $this->arr[array_rand($this->arr)];
        } else {
            if ($numberOfRandomItems > count($this->arr))
                throw new \InvalidArgumentException();
            $ans = [];
            while (count($ans) < $numberOfRandomItems) {
                $random = $this->arr[array_rand($this->arr)];
                if (!in_array($random, $ans)) {
                    $ans[] = $random;
                }
            }
            return collect($ans);
        }
    }

    public function reduce($callable, $carry = null)
    {
        $ans = null;
        foreach ($this->arr as $item) {
            $ans += $callable($carry, $item);
            $carry = null;
        }
        return $ans;
    }

    public function reject($callable)
    {
        $ans = [];
        foreach ($this->arr as $k => $v) {
            if (!$callable($v, $k)) {
                $ans[] = $v;
            }
        }
        return collect($ans);
    }

    public function replace($replaceItems)
    {
        $ans = [];
        $keys = array_keys($replaceItems);
        foreach ($this->arr as $k => $v) {
            if (in_array($k, $keys)) {
                $ans[] = $replaceItems[$k];
            } else {
                $ans[] = $v;
            }
        }
        foreach ($keys as $k) {
            if (!in_array($k, array_keys($this->arr))) {
                $ans[$k] = $replaceItems[$k];
            }
        }
        return collect($ans);
    }

    public function replaceRecursive($replaceItems)
    {
        $ans = [];
        $keys = array_keys($replaceItems);
        foreach ($this->arr as $k => $v) {
            if (in_array($k, $keys)) {
                if (is_array($replaceItems[$k]) && is_array($this->arr[$k])) {
                    $ans[] = collect($this->arr[$k])->replace($replaceItems[$k])->all();
                } else {
                    $ans[] = $replaceItems[$k];
                }
            } else {
                $ans[] = $v;
            }
        }
        foreach ($keys as $k) {
            if (!in_array($k, array_keys($this->arr))) {
                $ans[$k] = $replaceItems[$k];
            }
        }
        return collect($ans);
    }

    public function reverse()
    {
        return array_reverse($this->arr, true);
    }

    public function search($searchItem,$strict = false)
    {
        if(is_callable($searchItem)){
           foreach($this->arr as $k=>$v){
               if($searchItem($v,$k)) return $k;
           }
        }else{
            return array_search($searchItem,$this->arr,$strict);
        }
        return false;
    }


    public function sum()
    {
        return array_sum($this->arr);
    }



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
