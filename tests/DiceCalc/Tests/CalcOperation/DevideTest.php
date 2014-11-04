<?php
namespace DiceCalc\Tests\CalcOperation;

use \DiceCalc\CalcOperation;

class DivideTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider dataForDivide
     *
     * @param $num1
     * @param $num2
     * @param $expected
     */
    public function testDivide($num1, $num2, $expected)
    {
        $actual = CalcOperation::calc('/', $num1, $num2);

        $this->assertSame($expected, $actual);
    }

    public function dataForDivide()
    {
        return [
            [5, 2, 2.5],
            ['5', 2, 2.5],
            [5, '2', 2.5],
            ['5', '2', 2.5],
        ];
    }
}
