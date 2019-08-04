<?php

namespace Tests\Unit;

use App\FluentFactory\F;
use http\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class Student
{
    private $family;
    private $name;

    public function __construct($name, $family)
    {
        $this->name = $name;
        $this->family = $family;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($newName)
    {
        $this->name = $newName;
    }

    public function getFamily()
    {
        return $this->family;
    }

    public  function setFamily($newFamily)
    {
        $this->family = $newFamily;
    }
}


class FTest extends TestCase
{
    /** @test */
    public function return_value_of_functions_call_is_always_the_class_F()
    {
        $res = F::f(new Student('mohammad reza', 'mansouri'))->getName();
        $this->assertTrue($res instanceof F);
    }

    /** @test */
    function can_get_function_results()
    {
        $res = F::f(new Student('mohammad reza', 'mansouri'))->getFamily()->getName();
        $this->assertEquals('mohammad reza', $res->getLastResult());
    }

    /** @test */
    function can_send_input_arguments_to_functions()
    {
        $s = new Student('mohammad reza', 'mansouri');
        F::f($s)->setName('mohammad')->setFamily('man');
        $this->assertEquals('mohammad', $s->getName());
        $this->assertEquals('man', $s->getFamily());
    }

    /** @test */
    function throws_an_exception_if_a_method_does_not_exist()
    {
        $this->expectException(\InvalidArgumentException::class);
        $s = new Student('mohammad reza', 'mansouri');
        F::f($s)->setName('mohammad')->setAge('man');
    }

    
}
