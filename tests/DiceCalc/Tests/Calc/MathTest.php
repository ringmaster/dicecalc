<?php
namespace DiceCalc\Tests\Calc;

use DiceCalc\Calc;

class MathTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider diceMathProvider
     */
    public function testMath($expression, $expected)
    {
        $calc = new Calc($expression);

        $result = $calc();

        $this->assertEquals($expected, $result);
    }

    public function diceMathProvider()
    {
        return [
            ['0+0', 0],
            ['1+1', 2],
            ['1+0', 1],
            ['0+1', 1],
            ['10+1', 11],
            ['1*1', 1],
            ['1*2', 2],
            ['2*1', 2],
            ['4*5', 20],
            ['4+4*5', 24],
            ['(4+4)*5', 40],
            ['(100+9)*40', 4360],

            ['1-1', 0],
            ['1-10', -9],
            ['10-1', 9],
            ['4-4*5', -16],
            ['(4-4)*5', 0],
        ];
    }
}
