<?php
namespace DiceRoller\Tests\CalcOperation;

use \DiceRoller\CalcOperation;

class DivideTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider dataForDivide
     *
     * @param $num1
     * @param $num2
     * @param $expected
     */
    public function testDivide($num1, $num2, $expected)
    {
        $actual = CalcOperation::divide($num1, $num2);

        $this->assertSame($expected, $actual);
    }

    public function dataForDivide()
    {
        return array(
            array(5, 2, 2.5),
            array('5', 2, 2.5),
            array(5, '2', 2.5),
            array('5', '2', 2.5),
            array('a5', 2, false),
            array(5, 'a2', false),
            array('a5', 'a2', false),
        );
    }
}
