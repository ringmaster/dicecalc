<?php
namespace DiceRoller\Tests\CalcOperation;

use DiceRoller\CalcOperation;

class LessThanTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider dataForLessThan
     *
     * @param $num1
     * @param $num2
     * @param $expected
     */
    public function testLessThan($num1, $num2, $expected)
    {
        $actual = CalcOperation::lessthan($num1, $num2);

        $this->assertSame($expected, $actual);
    }

    public function dataForLessThan()
    {
        return array(
            array(1, 2, true),
            array('1', 2, true),
            array(1, '2', true),
            array('1', '2', true),
            array('a1', 2, false),
            array(1, 'a2', false),
            array('a1', 'a2', false),
        );
    }
}
