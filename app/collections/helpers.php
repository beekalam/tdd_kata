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
    foreach ($arr as $row) {
        if (is_array($row)) {
            flattenArray($row, $ans);
        } else {
            $ans[] = $row;
        }
    }
    return $ans;
}
