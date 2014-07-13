<?php
namespace DiceRoller\Tests\CalcOperation;

use \DiceRoller\CalcOperation;

class SubtractTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider dataForSubtract
     *
     * @param $num1
     * @param $num2
     * @param $expected
     */
    public function testSubtract($num1, $num2, $expected)
    {
        $actual = CalcOperation::subtract($num1, $num2);

        $this->assertSame($expected, $actual);
    }

    public function dataForSubtract()
    {
        return array(
            array(5, 2, 3),
            array('5', 2, 3),
            array(5, '2', 3),
            array('5', '2', 3),
            array('a5', 2, false),
            array(5, 'a2', false),
            array('a5', 'a2', false),
        );
    }
}
