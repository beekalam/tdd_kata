<?php

namespace Tests\Unit;

use App\lychrel\Lychrel;
use PHPUnit\Framework\TestCase;

class LychrelTest extends TestCase
{
    const LIMIT = 230;

    public function convergesAtIteration(int $n, int $iteration)
    {
        $this->assertEquals($iteration, Lychrel::convergesAtIteration($n, self::LIMIT));
    }

    private function isPalindrome($n)
    {
        $this->assertTrue(Lychrel::isPalindrome($n));
    }

    private function isNotPalindrome($n)
    {
        $this->assertFalse(Lychrel::isPalindrome($n));
    }

    /** @test */
    function facts()
    {
        $this->convergesAtIteration(1, 0);
        $this->convergesAtIteration(2, 0);
        $this->convergesAtIteration(10, 1);
        $this->convergesAtIteration(19, 2);
        $this->convergesAtIteration(78, 4);
        $this->convergesAtIteration(89, 24);

        $this->doesNotConverges(196);
    }

    private function doesNotConverges($n)
    {
        $this->convergesAtIteration($n, self::LIMIT);
    }

    /** @test */
    function palindromes()
    {
        $this->isPalindrome(1);
        $this->isPalindrome(11);
        $this->isPalindrome(121);
        $this->isPalindrome(12321);
        $this->isPalindrome('1234321');
    }

    /** @test */
    function nonPalindromes()
    {
        $this->isNotPalindrome(10);
        $this->isNotPalindrome(12331);
        $this->isNotPalindrome(1243321);
    }

    /** @test */
    function bigSum()
    {
        $this->assertEquals(strval(8+8), Lychrel::bigSum('8','8'));
        $this->assertEquals(strval(1234+1234), Lychrel::bigSum('1234','1234'));
        $this->assertEquals(9234+1239, Lychrel::bigSum('9234','1239'));
        $this->assertEquals(34+1239, Lychrel::bigSum('34','1239'));
        $this->assertEquals(999+12, Lychrel::bigSum('999','12'));
        $this->assertEquals(11, Lychrel::bigSum('10','01'));
        $this->assertEquals('100000000000000000000000000', Lychrel::bigSum('90000000000000000000000000','10000000000000000000000000'));

    }


}