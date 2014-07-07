<?php
namespace DiceRoller\Tests\Calc;

use DiceRoller\Calc;

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

            $result = $calc->calc();

            $i++;

        } while ($result !== true && $i < 10000);

        $this->assertTrue($result, sprintf('We never reached true from %s', $expression));
    }

    public function diceRollTargetProvider()
    {
        return array(
            array('d6 > 1'),
            array('d6 > 3'),
            array('d6 > 5'),

            array('d6 < 2'),
            array('d6 < 4'),
            array('d6 < 6'),

            array('2d6 > 2'),
            array('2d6 < 12'),

            array('2d6+5 > 7'),
            array('2d6+5 < 17'),

            array('2d6-1 > 1'),
            array('2d6-5 < 6'),
        );
    }
}
