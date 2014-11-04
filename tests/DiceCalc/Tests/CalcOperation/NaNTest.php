<?php
namespace DiceCalc\Tests\CalcOperation;

use \DiceCalc\CalcOperation;

class NaNTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider dataForAddNaN
     * @param $num1
     * @param $num2
     * @expectedException \Exception
     * @expectedExceptionMessage This is not a number
     */
    public function testAddNaN($num1, $num2)
    {
        CalcOperation::calc('+', $num1, $num2);
    }


    public function dataForAddNaN()
    {
        return [
            ['a5', 2],
            [5, 'a2'],
            ['a5', 'a2'],
        ];
    }
}
