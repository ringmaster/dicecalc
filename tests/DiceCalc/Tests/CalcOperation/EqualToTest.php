<?php
namespace DiceCalc\Tests\CalcOperation;

use \DiceCalc\CalcOperation;

class EqualToTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider dataForEqualTo
     *
     * @param $num1
     * @param $num2
     * @param $expected
     */
    public function testEqualTo($num1, $num2, $expected)
    {
        $actual = CalcOperation::calc('=', $num1, $num2);

        $this->assertSame($expected, $actual);
    }

    public function dataForEqualTo()
    {
        return [
            [1, 1, true],
            ['1', 1, true],
            [1, '1', true],
            ['1', '1', true],
        ];
    }
}
