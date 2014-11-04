<?php
namespace DiceCalc\Tests\CalcOperation;

use DiceCalc\CalcOperation;

class GreaterThanTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider dataForGreaterThan
     *
     * @param $num1
     * @param $num2
     * @param $expected
     */
    public function testGreaterThan($num1, $num2, $expected)
    {
        $actual = CalcOperation::calc('>', $num1, $num2);

        $this->assertSame($expected, $actual);
    }

    public function dataForGreaterThan()
    {
        return [
            [2, 1, true],
            ['2', 1, true],
            [2, '1', true],
            ['2', '1', true],
        ];
    }
}
