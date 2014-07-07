<?php
namespace DiceRoller\Tests\Calc;

use DiceRoller\Calc;

class BasicRollTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider basicRollProvider
     */
    public function testBasicRollOfDice($expression, $left, $right)
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

    public function basicRollProvider()
    {
        return array(
            array('d6', 1, 6),
            array('1d6', 1, 6),
            array('2d6', 2, 12),
            array('3d6', 3, 18),
            array('100d6', 100, 600),

            array('d20', 1, 20),
            array('1d20', 1, 20),
            array('2d20', 2, 40),

            array('d%', 1, 100),
            array('1d%', 1, 100),

            array('df', -1, 1),
            array('1df', -1, 1),
            array('2df', -2, 2),
            array('6df', -6, 6),
            array('100df', -100, 100),
        );
    }
}
