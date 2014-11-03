<?php
namespace DiceCalc\Tests\CalcOperation;

use \DiceCalc\CalcOperation;

class ExponentTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider dataForExponent
     *
     * @param $num1
     * @param $num2
     * @param $expected
     */
    public function testExponent($num1, $num2, $expected)
    {
        $actual = CalcOperation::exponent($num1, $num2);

        $this->assertSame($expected, $actual);
    }

    public function dataForExponent()
    {
        return [
            [2, 8, 256],
            ['2', 8, 256],
            [2, '8', 256],
            ['2', '8', 256],
            ['a2', 8, false],
            [2, 'a8', false],
            ['2a', '8a', false],
        ];
    }
}
