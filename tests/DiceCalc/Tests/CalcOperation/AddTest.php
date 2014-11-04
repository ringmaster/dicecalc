<?php
namespace DiceCalc\Tests\CalcOperation;

use \DiceCalc\CalcOperation;

class AddTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider dataForAdd
     *
     * @param $num1
     * @param $num2
     * @param $expected
     */
    public function testAdd($num1, $num2, $expected)
    {
        $actual = CalcOperation::calc('+', $num1, $num2);

        $this->assertSame($expected, $actual);
    }

    public function dataForAdd()
    {
        return [
            [5, 2, 7],
            ['5', 2, 7],
            [5, '2', 7],
            ['5', '2', 7],
        ];
    }
}
