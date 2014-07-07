<?php
namespace DiceRoller\Tests\Calc;

use DiceRoller\Calc;

class MathTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider diceMathProvider
     */
    public function testMath($expression, $expected)
    {
        $calc = new Calc($expression);

        $result = $calc->calc();

        $this->assertEquals($expected, $result);
    }

    public function diceMathProvider()
    {
        return array(
            //array('0+0', 0),
            array('1+1', 2),
            //array('1+0', 1),
            //array('0+1', 1),
            array('10+1', 11),
            array('1*1', 1),
            array('1*2', 2),
            array('2*1', 2),
            array('4*5', 20),
            array('4+4*5', 24),
            array('(4+4)*5', 40),
            array('(100+9)*40', 4360),

            array('1-1', 0),
            array('1-10', -9),
            array('10-1', 9),
            array('4-4*5', -16),
            array('(4-4)*5', 0),
        );
    }
}
