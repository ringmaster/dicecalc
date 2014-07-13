<?php
namespace DiceRoller\Tests\CalcOperation;

use \DiceRoller\CalcOperation;

class EqualToTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider dataForEqualTo
     *
     * @param $num1
     * @param $num2
     * @param $expected
     */
    public function testEqualTo($num1, $num2, $expected)
    {
        $actual = CalcOperation::equalto($num1, $num2);

        $this->assertSame($expected, $actual);
    }

    public function dataForEqualTo()
    {
        return array(
            array(1, 1, true),
            array('1', 1, true),
            array(1, '1', true),
            array('1', '1', true),
            array('a1a', 1, false),
            array(1, 'a1a', false),
            array('a1a', 'a1a', false),
        );
    }
}
