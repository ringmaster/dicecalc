<?php
namespace DiceCalc\Tests\Calc;

use DiceCalc\Calc;
use DiceCalc\Random;

class BasicRollTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider basicRollProvider
     */
    public function testBasicRollOfDice($expression, $left, $right)
    {
        $calc = new Calc($expression);

        $result = $calc();

        $this->assertTrue(
            is_numeric($result),
            sprintf('Calc::calc(%s) result is not numeric: %s', $expression, $result)
        );


        Random::queue_min();
        $calc = new Calc($expression);
        $result = $calc();
        $this->assertTrue(
            ($result == $left),
            sprintf('Calc::calc(%s) result %s is not not bigger or equal %d', $expression, $result, $left)
        );

        Random::queue_max();
        $calc = new Calc($expression);
        $result = $calc();
        $this->assertTrue(
            ($result == $right),
            sprintf('Calc::calc(%s) result %s is not not less or equal %d', $expression, $result, $right)
        );

        Random::clear_queue();
    }

    public function basicRollProvider()
    {
        return [
            ['d6', 1, 6],
            ['1d6', 1, 6],
            ['2d6', 2, 12],
            ['3d6', 3, 18],
            ['100d6', 100, 600],

            ['d20', 1, 20],
            ['1d20', 1, 20],
            ['2d20', 2, 40],

            ['d%', 1, 100],
            ['1d%', 1, 100],

            ['df', -1, 1],
            ['1df', -1, 1],
            ['2df', -2, 2],
            ['6df', -6, 6],
            ['100df', -100, 100],
        ];
    }
}
