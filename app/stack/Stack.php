<?php

namespace App\Stack;
use Exception;

interface Stack
{
    public function isEmpty();

    public function getSize();

    public function push($element);

    public function pop();

    public function top();
}

class Overflow extends Exception
{

}

class Underflow extends Exception
{

}

class IllegalCapacity extends Exception
{
}


class EmptyException extends Exception
{
}
