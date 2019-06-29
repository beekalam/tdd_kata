<?php


namespace App\lychrel;


class Lychrel
{
    public static function convergesAtIteration($n, int $limit)
    {
        return self::converge($n, 0, $limit);
    }

    private static function converge($n, $iteration, $limit)
    {
        if (!self::isPalindrome($n) && $iteration < $limit) {
            return self::converge(self::bigSum($n, self::reverse($n)), $iteration + 1, $limit);
        } else
            return $iteration;
    }

    public static function isPalindrome($n)
    {
        return self::reverse($n) == strval($n);
    }

    public static function reverse($n)
    {
        return strrev($n);
        // $str = strrev(strval($n));
        // $i = 0;
        // while($i < strlen($str) && $str[$i] === "0") $i++;
        //
        // $ans = '';
        // for(;$i<strlen($str);$i++) $ans .= $str[$i];
        //
        // return $ans;
    }

    public static function bigSum($n, $m)
    {
        $n = strval($n);
        $m = strval($m);
        if (strlen($n) < strlen($m)) {
            $n = str_repeat('0', strlen($m) - strlen($n)) . $n;
        }

        if (strlen($m) < strlen($n)) {
            $m = str_repeat('0', strlen($n) - strlen($m)) . $m;
        }

        $ans = '';
        $carry = 0;
        for ($i = strlen($n) - 1; $i >= 0; $i--) {
            $sum = intval($n[$i]) + intval($m[$i]) + $carry;
            $carry = 0;
            if ($sum >= 10) {
                $carry = 1;
                $ans = strval($sum - 10) . $ans;
            } else {
                $ans = strval($sum) . $ans;
            }
        }
        if ($carry == 1)
            $ans = '1' . $ans;
        return $ans;
    }

}