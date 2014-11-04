<?php
namespace DiceCalc\Tests\CalcOperation;

use \DiceCalc\CalcOperation;

class SubtractTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider dataForSubtract
     *
     * @param $num1
     * @param $num2
     * @param $expected
     */
    public function testSubtract($num1, $num2, $expected)
    {
        $actual = CalcOperation::calc('-', $num1, $num2);

        $this->assertSame($expected, $actual);
    }

    public function dataForSubtract()
    {
        return [
            [5, 2, 3],
            ['5', 2, 3],
            [5, '2', 3],
            ['5', '2', 3],
        ];
    }
}
