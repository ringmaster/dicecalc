<?php
namespace DiceCalc\Tests\Calc;

use DiceCalc\Calc;

class CustomDieTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider customNumericDieProvider
     */
    public function testCustomNumericDie($expression, array $availableValues)
    {

        $allFound = false;
        $i = 0;
        $foundElements = [];

        do {
            $calc = new Calc($expression);

            $result = $calc();

            $this->assertTrue(is_numeric($result));

            $i++;

            $this->assertTrue(
                in_array($result, $availableValues),
                sprintf('Roll result %s not found in expected array', $result)
            );

            if (!array_key_exists($result, $foundElements)) {
                $foundElements[$result] = 0;
            }

            $foundElements[$result]++;

            if (count($foundElements) == count($availableValues)) {
                $allFound = true;
            }

        } while ($allFound === false && $i < 1000);

        $this->assertTrue($allFound, sprintf('Not all variants passed for %s', $expression));

    }

    public function customNumericDieProvider()
    {
        return [
            [
                'd[1,2,2,3,3,4]',
                [1, 2, 3, 4],
            ],
            [
                'd[2,4,6]',
                [2,4,6],
            ],
        ];
    }

    /**
     * @dataProvider customColorDieProvider
     */
    public function testCustomColorDie($expression, array $availableValues)
    {

        $allFound = false;
        $i = 0;
        $foundElements = [];

        $checkValues = [];

        foreach ($availableValues as $value) {
            $checkValues[] = '['.$expression.':'.$value.']';
        }

        do {
            $calc = new Calc($expression);

            $result = $calc();

            $this->assertInstanceOf('DiceCalc\CalcDice', $result);

            $resultString = (string) $result->value();

            $i++;

            $this->assertTrue(
                in_array($resultString, $checkValues),
                sprintf('Roll result %s not found in expected array', $result)
            );

            if (!array_key_exists($resultString, $foundElements)) {
                $foundElements[$resultString] = 0;
            }

            $foundElements[$resultString]++;

            if (count($foundElements) == count($availableValues)) {
                $allFound = true;
            }

        } while ($allFound === false && $i < 1000);

        $this->assertTrue($allFound, sprintf('Not all variants passed for %s', $expression));

    }

    public function customColorDieProvider()
    {
        return [
            [
                'd[red,green,blue,yellow]',
                ['red', 'green', 'blue', 'yellow'],
            ],
            [
                'd[red,green,blue]',
                ['red', 'green', 'blue'],
            ],
        ];
    }
}
