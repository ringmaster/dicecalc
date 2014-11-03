<?php
namespace DiceCalc\Tests\CalcOperation;

use \DiceCalc\CalcOperation;

class CalcTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider dataForCalc
     *
     * @param $operator
     * @param $num1
     * @param $num2
     * @param $expected
     */
    public function testCalc($operator, $num1, $num2, $expected)
    {
        $actual = CalcOperation::calc($operator, $num1, $num2);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException \Exception
     */
    public function testCalcUnknownOperator() {
        CalcOperation::calc('?', 1, 2);
    }

    public function dataForCalc()
    {
        return [
            ['+', 2, 5, 7],
            ['*', 2, 5, 10],
            ['-', 2, 5, 3],
            ['/', 2, 5, 2.5],
            ['^', 2, 5, 25],
            ['>', 2, 5, true],
            ['<', 2, 5, false],
            ['<', 5, 2, true],
            ['>', 5, 2, false],
            ['=', 2, 2, true],
            ['=', 3, 2, false],
        ];
    }
}
