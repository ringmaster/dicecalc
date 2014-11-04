<?php
namespace DiceCalc\Tests\Calc;

use DiceCalc\Calc;

class BasicRollWithMathTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider diceRollProvider
     */
    public function testRollOfDice($expression, $left, $right)
    {
        // I hope 100 of iterations is enough to test random
        for ($i = 0; $i < 100; $i++) {
            $calc = new Calc($expression);

            $result = $calc();

            $this->assertTrue(
                is_numeric($result),
                sprintf('Calc::calc(%s) result is not numeric: %s', $expression, $result)
            );
            $this->assertTrue(
                ($result >= $left),
                sprintf('Calc::calc(%s) result %s is not not bigger or equal %d', $expression, $result, $left)
            );
            $this->assertTrue(
                ($result <= $right),
                sprintf('Calc::calc(%s) result %s is not not less or equal %d', $expression, $result, $right)
            );
        }
    }

    public function diceRollProvider()
    {
        return [
            ['d6+1', 2, 7],
            ['1d6+1', 2, 7],
            ['2d6+1', 3, 13],
            ['3d6+3', 6, 21],
            ['100d6+10', 110, 610],

            ['d20+5', 6, 26],
            ['1d20+3', 4, 23],
            ['2d20+4', 6, 44],

            ['d%+3', 4, 104],
            ['1d%+55', 56, 155],

            ['df+3', 2, 4],
            ['1df+5', 4, 6],
            ['2df+8', -6, 10],
            ['6df+1', -5, 7],
            ['100df+3', -97, 103],

            ['d6+d3', 2, 9],
            ['1d6+1d3', 2, 9],
            ['2d6+1d3', 3, 15],
            ['1d6+2d3', 3, 12],
            ['10d6+5d3', 15, 85],
        ];
    }
}
