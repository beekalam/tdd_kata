<?php


namespace App\Romancalculator;


class RomanCalculator
{
    private $nums = [
        '1'    => 'I',
        '2'    => 'II',
        '3'    => 'III',
        '4'    => 'IV',
        '5'    => 'V',
        '6'    => 'VI',
        '7'    => 'VII',
        '8'    => 'VIII',
        '9'    => 'IX',
        '10'   => 'X',
        '20'   => 'XX',
        '30'   => 'XXX',
        '40'   => 'XL',
        '50'   => 'L',
        '60'   => 'LX',
        '70'   => 'LXX',
        '80'   => 'LXXX',
        '90'   => 'XC',
        '100'  => 'C',
        '200'  => 'CC',
        '300'  => 'CCC',
        '400'  => 'CD',
        '500'  => 'D',
        '600'  => 'DC',
        '700'  => 'DCC',
        '800'  => 'DCCC',
        '900'  => 'CM',
        '1000' => 'M',
        '2000' => 'MM',
        '3000' => 'MMM',
    ];

    public function calc($num)
    {
        if (isset($this->nums[$num])) {
            return $this->nums[$num];
        } else if ($num > 10 && $num < 100) {
            if ($num % 10 <= 9) {
                $index = $num - ($num % 10);
                return $this->nums[$index] . $this->calc($num % 10);
            }
        } else if ($num > 100 && $num < 1000) {
            $index = $num - ($num % 100);
            return $this->nums[$index] . $this->calc($num % 100);
        }else if($num > 1000 && $num < 10000){
            $index = $num - ($num % 1000);
            return $this->nums[$index] . $this->calc($num % 1000);
        }
    }
}