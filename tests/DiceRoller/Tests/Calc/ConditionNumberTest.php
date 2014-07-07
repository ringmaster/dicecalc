<?php
namespace DiceRoller\Tests\Calc;

use DiceRoller\Calc;

class ConditionNumberTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider diceMathConditionProvider
     */
    public function testMathCondition($expression, $expected)
    {
        $calc = new Calc($expression);

        $result = $calc->calc();

        $this->assertEquals($expected, $result);
    }

    public function diceMathConditionProvider()
    {
        return array(
            array('1+1 = 2', true),
            array('3+8 = 11', true),

            array('1+1 > 1', true),
            array('1+1 > 3', false),

            array('1+1 < 1', false),
            array('1+1 < 3', true),

            array('4+4 > 7', true),
            array('4+4 > 8', false),
            array('4+4 > 9', false),
        );
    }
}
