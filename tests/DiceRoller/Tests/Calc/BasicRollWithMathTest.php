<?php
namespace DiceRoller\Tests\Calc;

use DiceRoller\Calc;

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

            $result = $calc->calc();

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
        return array(
            array('d6+1', 2, 7),
            array('1d6+1', 2, 7),
            array('2d6+1', 3, 13),
            array('3d6+3', 6, 21),
            array('100d6+10', 110, 610),

            array('d20+5', 6, 26),
            array('1d20+3', 4, 23),
            array('2d20+4', 6, 44),

            array('d%+3', 4, 104),
            array('1d%+55', 56, 155),

            array('df+3', 2, 4),
            array('1df+5', 4, 6),
            array('2df+8', -6, 10),
            array('6df+1', -5, 7),
            array('100df+3', -97, 103),

            array('d6+d3', 2, 9),
            array('1d6+1d3', 2, 9),
            array('2d6+1d3', 3, 15),
            array('1d6+2d3', 3, 12),
            array('10d6+5d3', 15, 85),
        );
    }
}
