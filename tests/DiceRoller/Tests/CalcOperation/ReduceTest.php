<?php
namespace DiceRoller\Tests\CalcOperation;

use DiceRoller\CalcOperation;
use DiceRoller\Calc;

class ReduceTest extends \PHPUnit_Framework_TestCase
{

    public function testReduceOnNumeric()
    {
        $actual = CalcOperation::reduce('3');

        $this->assertEquals(3, $actual);
    }

    public function testReduceOnThrowingException()
    {
        $this->setExpectedException('Exception');

        $param = new \stdClass();

        CalcOperation::reduce($param);
    }

    public function testReduceOnCalc()
    {
        $param = new Calc('1d6');

        $actual = CalcOperation::reduce($param);

        $this->assertTrue(($actual >= 1));
        $this->assertTrue(($actual <= 6));
    }
}
