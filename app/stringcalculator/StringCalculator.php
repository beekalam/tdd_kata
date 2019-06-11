<?php

namespace App\Stringcalculator;
/**
 * TDD kata from https://osherove.com/tdd-kata-1
 */
class StringCalculator
{

    private $negatives = [];
    private $CalledCount;
    private $numbers;

    public function Add($numbers)
    {
        $this->init($numbers);
        if (empty($numbers)) return 0;

        if ($this->hasOptionalDelimiter())
            $numbers = $this->PruneOptionalDelimiter();

        $numbers = $this->MultiExplode($this->getDelimiters(), $numbers);
        return $this->Sum($numbers);
    }

    private function init($numbers)
    {
        $this->CalledCount++;
        $this->negatives = [];
        $this->numbers = $numbers;
    }

    private function MultiExplode($delimiters, $numbers)
    {
        $ready = str_replace($delimiters, $delimiters[0], $numbers);
        $launch = explode($delimiters[0], $ready);
        return $launch;
    }


    private function getDelimiters()
    {
        if ($this->hasOptionalDelimiter($this->numbers)) {
            $delimiter_part = $this->delimiterPart($this->numbers);

            if (strlen($delimiter_part) == 1)
                return [$delimiter_part];
            else {
                preg_match_all("/\[([^\]]+)\]/", $delimiter_part, $matches);
                return $matches[1];
            }
        }

        return [',', '\n'];
    }

    private function hasOptionalDelimiter()
    {
        return ($this->numbers[0] == '/' && $this->numbers[1] == '/');
    }

    private function Sum($numbers)
    {
        $sum = $this->ArraySum($numbers);

        if (!empty($this->negatives)) {
            $ex = new NegativesNotAllowed();
            $ex->SetMessage(implode(',', $this->negatives));
            throw $ex;
        }

        return $sum;
    }

    private function ArraySum($numbers)
    {
        $sum = 0;
        foreach ($numbers as $num) {
            if (intval($num) < 0)
                $this->negatives[] = $num;
            if (intval($num) < 1000)
                $sum += $num;
        }
        return $sum;
    }

    public function GetCalledCount()
    {
        return $this->CalledCount;
    }

    private function PruneOptionalDelimiter()
    {
        if ($this->hasOptionalDelimiter($this->numbers)) {
            $optional_delimiter = "//" . $this->delimiterPart($this->numbers) . "\n";
            $numbers = substr($this->numbers, strlen($optional_delimiter));
        } else {
            $numbers = substr($this->numbers, 5);
        }
        return $numbers;
    }

    /**
     * @param $numbers
     * @return bool|string
     */
    private function delimiterPart($numbers)
    {
        $first_new_line_pos = strpos($numbers, "\n");
        $first_bracket_pos = strpos($numbers, '[', $first_new_line_pos);
        if ($first_bracket_pos === false)
            $delimiter_part = substr($numbers, 2, $first_new_line_pos - 2);
        else
            $delimiter_part = substr($numbers, 2, 1);
        return $delimiter_part;
    }


}

class NegativesNotAllowed extends \Exception
{
    public function SetMessage($message)
    {
        $this->message = $message;
    }
}