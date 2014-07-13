<?php
namespace DiceRoller\Tests\CalcOperation;

use \DiceRoller\CalcOperation;

class CalcTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider dataForCalc
     *
     * @param $operator
     * @param $num1
     * @param $num2
     * @param $expected
     */
    public function testCalc($operator, $num1, $num2, $expected)
    {
        $actual = CalcOperation::calc($operator, $num1, $num2);

        $this->assertEquals($expected, $actual);
    }

    public function dataForCalc()
    {
        return array(
            array('+', 2, 5, 7),
            array('*', 2, 5, 10),
            array('-', 2, 5, 3),
            array('/', 2, 5, 2.5),
            array('^', 2, 5, 25),
            array('>', 2, 5, true),
            array('<', 2, 5, false),
            array('<', 5, 2, true),
            array('>', 5, 2, false),
            array('=', 2, 2, true),
            array('=', 3, 2, false),
            array('?', 3, 2, null),
        );
    }
}
