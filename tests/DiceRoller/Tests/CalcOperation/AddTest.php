<?php
namespace DiceRoller\Tests\CalcOperation;

use \DiceRoller\CalcOperation;

class AddTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider dataForAdd
     *
     * @param $num1
     * @param $num2
     * @param $expected
     */
    public function testAdd($num1, $num2, $expected)
    {
        $actual = CalcOperation::add($num1, $num2);

        $this->assertSame($expected, $actual);
    }

    public function dataForAdd()
    {
        return array(
            array(5, 2, 7),
            array('5', 2, 7),
            array(5, '2', 7),
            array('5', '2', 7),
            array('a5', 2, false),
            array(5, 'a2', false),
            array('a5', 'a2', false),
        );
    }
}
