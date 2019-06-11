<?php

namespace Tests\Unit;

use App\Stringcalculator\NegativesNotAllowed;
use App\Stringcalculator\StringCalculator;
use PHPUnit\Framework\TestCase;
use Exception;

class StringCalculatorTest extends TestCase
{
    private $stringcalculator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->stringcalculator = new StringCalculator();
    }

    /** @test */
    function when_given_empty_string_returns_zero()
    {
        $this->assertEquals(0, $this->stringcalculator->Add(''));
    }

    /** @test */
    function when_give_1_returns_1()
    {
        $this->assertEquals(1, $this->stringcalculator->Add('1'));
    }

    /** @test */
    function when_give_one_and_two_returns_3()
    {
        $this->assertEquals(3, $this->stringcalculator->Add('1,2'));
    }

    /** @test */
    function when_given_multiple_numbers_should_return_correct_sum()
    {
        $this->assertEquals($sum = 1 + 2 + 3 + 4 + 5 + 6, $this->stringcalculator->Add('1,2,3,4,5,6'));
    }

    /** @test */
    function when_given_new_line_delimiter_should_give_correct_sum()
    {
        $this->assertEquals($sum = 1 + 2 + 3 + 4 + 5 + 6, $this->stringcalculator->Add('1,2\n3\n4,5,6'));
    }

    /** @test */
    function when_given_different_delimiters_should_give_correct_sum()
    {
        $this->assertEquals($sum = 1 + 2 + 3, $this->stringcalculator->Add("//@\n1@2@3"));
    }

    /** @test */
    function when_given_negative_number_should_throw_NegativesNotAllowed()
    {
        $this->expectException(NegativesNotAllowed::class);
        $this->stringcalculator->Add('1,-2,-3');
    }

    /** @test */
    function when_given_multiple_negative_numbers_exception_message_should_show_them()
    {
        try {
            $this->stringcalculator->Add('1,-2,-3');
        }catch (Exception $e){
            $this->assertEquals('-2,-3',$e->getMessage());
        }
    }

    /** @test */
    function when_called_multiple_times_should_return_number_of_calls()
    {
        $this->stringcalculator->Add('1,2');
        $this->stringcalculator->Add('2,3');
        $this->stringcalculator->Add('4,5');
        $this->assertEquals(3,$this->stringcalculator->GetCalledCount());
    }

    /** @test */
    function when_given_numbers_bigger_than_1000_should_be_ignored()
    {
        $this->assertEquals(1+2+3,$this->stringcalculator->Add('1,2,3,1000'));
    }

    /** @test */
    function when_given_delimiter_of_any_length_should_give_correct_sum()
    {
        // delimiter format "//[delimiter]\n";
        $this->assertEquals(1+2+3,$this->stringcalculator->Add("//[***]\n1***2***3"));
        $this->assertEquals(1+2+3,$this->stringcalculator->Add("//[*]\n1*2*3"));
    }

    /** @test */
    function when_given_multiple_delimiters_should_give_correct_sum()
    {
        // delimiter format "//[delime1][delime2]\n"
        $this->assertEquals(1+2+3,$this->stringcalculator->Add("//[*][%]\n1*2%3"));
        $this->assertEquals(1+2+3,$this->stringcalculator->Add("//[**][%%]\n1**2%%3"));
    }

}