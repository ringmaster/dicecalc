<?php
namespace DiceCalc\Tests\CalcOperation;

use DiceCalc\CalcOperation;
use DiceCalc\Calc;

class ReduceTest extends \PHPUnit_Framework_TestCase
{

    public function testReduceOnNumeric()
    {
        $actual = CalcOperation::reduce('3');

        $this->assertEquals(3, $actual);
    }

    /**
     * @throws \Exception
     * @expectedException \Exception
     */
    public function testReduceOnThrowingException()
    {
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
