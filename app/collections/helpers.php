<?php


use App\collections\Collection;

function collect($array)
{
    return new Collection($array);
}

function isAssociative($array)
{
    foreach ($array as $key => $value) {
        if (is_string($key)) {
            return true;
        }
    }
    return false;
}

function flattenArray($arr, &$ans = [])
{
    $isAssociative = isAssociative($arr);
    foreach ($arr as $k => $row) {
        if (is_array($row)) {
            flattenArray($row, $ans);
        } else {
            if ($isAssociative) {
                $ans[$k] = $row;
            } else {
                $ans[] = $row;
            }
        }
    }
    return $ans;
}
