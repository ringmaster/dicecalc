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
        $actual = CalcOperation::equalto($num1, $num2);

        $this->assertSame($expected, $actual);
    }

    public function dataForEqualTo()
    {
        return [
            [1, 1, true],
            ['1', 1, true],
            [1, '1', true],
            ['1', '1', true],
            ['a1a', 1, false],
            [1, 'a1a', false],
            ['a1a', 'a1a', false],
        ];
    }
}
