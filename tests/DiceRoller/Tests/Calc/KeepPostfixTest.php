<?php
namespace DiceCalc\Tests\Calc;

use DiceCalc\Calc;

class KeepPostfixTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider dataForKeepPostfixGreaterThan
     *
     * @param $expression
     * @param $rollsNum
     * @param $minExpected
     * @param $maxExpected
     */
    public function testKeepPostfixGreaterThan($expression, $rollsNum, $minExpected, $maxExpected)
    {
        $minResult = $minExpected;
        $maxResult = $rollsNum * $maxExpected;

        for ($i = 0; $i < 100; $i++) {

            $calc = new Calc($expression);

            $result = $calc();

            $this->assertTrue(
                is_numeric($result),
                sprintf('Calc::calc(%s) result is not numeric: %s', $expression, $result)
            );

            if ($result > 0) {
                $this->assertTrue(
                    ($result >= $minResult),
                    sprintf('Calc::calc(%s) result %s is not not bigger or equal %d', $expression, $result, $minResult)
                );
                $this->assertTrue(
                    ($result <= $maxResult),
                    sprintf('Calc::calc(%s) result %s is not not less or equal %d', $expression, $result, $maxResult)
                );
            }
        }
    }

    public function dataForKeepPostfixGreaterThan()
    {
        return [
            ['5d6 keep > 4', 5, 4, 6],
            ['5d6k>4', 5, 4, 6]
        ];
    }

    /**
     * @dataProvider dataForKeepPostfixLessThan
     *
     * @param $expression
     * @param $rollsNum
     * @param $minExpected
     * @param $maxExpected
     */
    public function testKeepPostfixLessThan($expression, $rollsNum, $minExpected, $maxExpected)
    {
        $minResult = $minExpected;
        $maxResult = $rollsNum * $maxExpected;

        for ($i = 0; $i < 100; $i++) {

            $calc = new Calc($expression);

            $result = $calc();

            $this->assertTrue(
                is_numeric($result),
                sprintf('Calc::calc(%s) result is not numeric: %s', $expression, $result)
            );

            if ($result > 0) {
                $this->assertTrue(
                    ($result >= $minResult),
                    sprintf('Calc::calc(%s) result %s is not not bigger or equal %d', $expression, $result, $minResult)
                );
                $this->assertTrue(
                    ($result <= $maxResult),
                    sprintf('Calc::calc(%s) result %s is not not less or equal %d', $expression, $result, $maxResult)
                );
            }
        }
    }

    public function dataForKeepPostfixLessThan()
    {
        return [
            ['5d6 keep < 4', 5, 1, 3],
            ['5d6k<4', 5, 1, 3]
        ];
    }

}
