<?php
namespace DiceRoller\Tests\CalcOperation;

use \DiceRoller\CalcOperation;

class MultiplyTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider dataForMultiply
     *
     * @param $num1
     * @param $num2
     * @param $expected
     */
    public function testMultiply($num1, $num2, $expected)
    {
        $actual = CalcOperation::multiply($num1, $num2);

        $this->assertSame($expected, $actual);
    }

    public function dataForMultiply()
    {
        return array(
            array(5, 2, 10),
            array('5', 2, 10),
            array(5, '2', 10),
            array('5', '2', 10),
            array('a5', 2, false),
            array(5, 'a2', false),
            array('a5', 'a2', false),
        );
    }
}
