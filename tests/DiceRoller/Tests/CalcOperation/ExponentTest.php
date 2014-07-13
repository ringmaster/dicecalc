<?php
namespace DiceRoller\Tests\CalcOperation;

use \DiceRoller\CalcOperation;

class ExponentTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider dataForExponent
     *
     * @param $num1
     * @param $num2
     * @param $expected
     */
    public function testExponent($num1, $num2, $expected)
    {
        $actual = CalcOperation::exponent($num1, $num2);

        $this->assertSame($expected, $actual);
    }

    public function dataForExponent()
    {
        return array(
            array(2, 8, 256),
            array('2', 8, 256),
            array(2, '8', 256),
            array('2', '8', 256),
            array('a2', 8, false),
            array(2, 'a8', false),
            array('2a', '8a', false),
        );
    }
}
