<?php
namespace DiceRoller\Tests\CalcOperation;

use DiceRoller\CalcOperation;

class GreaterThanTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider dataForGreaterThan
     *
     * @param $num1
     * @param $num2
     * @param $expected
     */
    public function testGreaterThan($num1, $num2, $expected)
    {
        $actual = CalcOperation::greaterthan($num1, $num2);

        $this->assertSame($expected, $actual);
    }

    public function dataForGreaterThan()
    {
        return array(
            array(2, 1, true),
            array('2', 1, true),
            array(2, '1', true),
            array('2', '1', true),
            array('a2', 1, false),
            array(2, 'a1', false),
            array('a2', 'a2', false),
        );
    }
}
