<?php
namespace DiceCalc\Tests\Calc;

use DiceCalc\Calc;

class TargetNumberTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider diceRollTargetProvider
     */
    public function testRollCondition($expression)
    {
        $i = 0;

        do {
            $calc = new Calc($expression);

            $result = $calc();

            $i++;

        } while ($result !== true && $i < 10000);

        $this->assertTrue($result, sprintf('We never reached true from %s', $expression));
    }

    public function diceRollTargetProvider()
    {
        return [
            ['d6 > 1'],
            ['d6 > 3'],
            ['d6 > 5'],

            ['d6 < 2'],
            ['d6 < 4'],
            ['d6 < 6'],

            ['2d6 > 2'],
            ['2d6 < 12'],

            ['2d6+5 > 7'],
            ['2d6+5 < 17'],

            ['2d6-1 > 1'],
            ['2d6-5 < 6'],
        ];
    }
}
