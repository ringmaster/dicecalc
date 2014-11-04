<?php
namespace DiceCalc\Tests\Calc;

use DiceCalc\Calc;

class ConditionNumberTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider diceMathConditionProvider
     */
    public function testMathCondition($expression, $expected)
    {
        $calc = new Calc($expression);

        $result = $calc();

        $this->assertEquals($expected, $result);
    }

    public function diceMathConditionProvider()
    {
        return [
            ['1+1 = 2', true],
            ['3+8 = 11', true],

            ['1+1 > 1', true],
            ['1+1 > 3', false],

            ['1+1 < 1', false],
            ['1+1 < 3', true],

            ['4+4 > 7', true],
            ['4+4 > 8', false],
            ['4+4 > 9', false],
        ];
    }
}
