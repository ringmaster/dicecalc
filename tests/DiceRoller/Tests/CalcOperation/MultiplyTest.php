<?php
namespace DiceCalc\Tests\CalcOperation;

use \DiceCalc\CalcOperation;

class MultiplyTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider dataForMultiply
     *
     * @param $num1
     * @param $num2
     * @param $expected
     */
    public function testMultiply($num1, $num2, $expected)
    {
        $actual = CalcOperation::multiply($num1, $num2);

        $this->assertSame($expected, $actual);
    }

    public function dataForMultiply()
    {
        return [
            [5, 2, 10],
            ['5', 2, 10],
            [5, '2', 10],
            ['5', '2', 10],
            ['a5', 2, false],
            [5, 'a2', false],
            ['a5', 'a2', false],
        ];
    }
}
